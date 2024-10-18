<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use RateLimiter;
use Str;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.show', ['user' => Auth::user()]);
    }

    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $key = 'profile-update' . $user->id;
        if (RateLimiter::tooManyAttempts($key, 5)) {
            Log::warning('Too many profile update attempts', ['userId' => $user->id]);
            return Redirect::back()->withErrors(['Too many attempts. Please try again later.']);
        }
        RateLimiter::hit($key, 60);

        try {
            $rules = [
                'name' => ['sometimes', 'string', 'min:3', 'max:255', 'regex:/^[\pL\s\-]+$/u'],
                'email' => ['sometimes', 'string', 'email:rfc,dns', 'max:255', 'unique:users,email,' . $user->id],
                'image_path' => ['nullable', 'file', 'mimes:jpeg,png,jpg', 'max:2048'],
            ];

            $messages = [
                'name.min' => 'Name must contain at least 3 characters.',
                'name.max' => 'Name cannot exceed 255 characters.',
                'name.regex' => 'Name can only contain letters and spaces.',
                'email.email' => 'Please enter a valid email address.',
                'email.unique' => 'This email is already registered.',
                'image_path.image' => 'The profile picture must be an image.',
                'image_path.mimes' => 'The profile picture must be a valid image file (jpeg, jpg, png)!',
                'image_path.max' => 'The profile picture cannot exceed 2MB in size.',
            ];

            if ($request->filled('password')) {
                $rules['current_password'] = ['required'];
                $rules['password'] = [
                    'required', 
                    'confirmed', 
                    'min:10',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{10,}$/',
                    Rules\Password::defaults(),
                ];

                $messages['current_password.required'] = 'Current password is required to change password.';
                $messages['password.confirmed'] = 'Password confirmation does not match.';
                $messages['password.min'] = 'Password must be at least 10 characters long.';
                $messages['password.regex'] = 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.';
            }

            $validated = $request->validate($rules, $messages);

            $updated = false;

            if ($request->filled('name') && $user->name !== $validated['name']) {
                $user->name = strip_tags($validated['name']);
                $updated = true;
            }

            if ($request->filled('email') && $user->email !== $validated['email']) {
                $user->email = filter_var($validated['email'], FILTER_SANITIZE_EMAIL);
                $updated = true;
            }

            if ($request->filled('password')) {
                if (!Hash::check($user->salt . $request->current_password, $user->password)) {
                    throw ValidationException::withMessages(['current_password' => ['The provided current password is incorrect.']]);
                }
                $user->password = Hash::make($user->salt . $validated['password']);
                $updated = true;
            }

            if ($request->hasFile('image_path')) {
                $uploadedFile = $request->file('image_path');

                try {
                    $tempPath = $uploadedFile->getPathname();

                    $imageInfo = @getimagesize($tempPath);
                    if ($imageInfo === false) {
                        Log::error('Invalid image type: ' . $uploadedFile->getClientMimeType());
                        throw ValidationException::withMessages([
                            'image_path' => ['The uploaded file is not a valid image.']
                        ]);
                    }

                    $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/jpg'];
                    if (!in_array($imageInfo['mime'], $allowedMimeTypes)) {
                        throw ValidationException::withMessages([
                            'image_path' => ['The uploaded file type is not allowed.']
                        ]);
                    }

                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($tempPath);

                    $imageName = Str::uuid() . '.jpg';
                    $path = storage_path('app/private/user_profiles/' . $imageName);

                    $image->cover(300, 300)
                        ->toJpeg(80)
                        ->save($path);

                    $imagePath = 'user_profiles/' . $imageName;

                    if (!exif_imagetype($path)) {
                        unlink($path);
                        throw ValidationException::withMessages([
                            'image_path' => ['The uploaded file could not be processed as an image.']
                        ]);
                    }

                    if ($user->image_path && Storage::disk('private')->exists($user->image_path)) {
                        Storage::disk('private')->delete($user->image_path);
                    }
                    
                    $user->image_path = $imagePath;
                    $updated = true;

                } catch (ValidationException $e) {
                    throw $e;
                } catch (\Exception $e) {
                    Log::error('Image processing failed: ' . $e->getMessage());
                    throw ValidationException::withMessages([
                        'image_path' => ['An error occurred while processing the image. Update blocked for security reasons.']
                    ]);
                }
            }

            if ($updated) {
                $user->save();
                Log::info('Profile updated for user ID: ' . $user->id);
                return redirect()->route('profile.show')->with('status', 'profile-updated');
            }

            return redirect()->route('profile.edit')->with('status', 'no-changes');

        } catch (ValidationException $e) {
            return redirect()->route('profile.edit')->withErrors($e->errors())->withInput($request->except('password', 'current_password', 'password_confirmation'));
        } catch (\Exception $e) {
            Log::error('Profile update failed', ['user_id' => $user->id, 'error' => $e->getMessage()]);
            return back()->withErrors(['unexpected' => 'An unexpected error occurred. Please try again.'])->withInput($request->except('password', 'current_password', 'password_confirmation'));
        }
    }

    public function destroy(Request $request): RedirectResponse
    {
        try {
            $request->validateWithBag('userDeletion', [
                'password' => ['required', 'current_password'],
            ]);

            $user = $request->user();

            Log::warning('Account deletion initiated for user ID: ' . $user->id);

            Auth::logout();

            if ($user->image_path && Storage::disk('private')->exists($user->image_path)) {
                Storage::disk('private')->delete($user->image_path);
            }
            
            $user->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            Log::info('Account successfully deleted for user ID: ' . $user->id);

            return Redirect::to('/')->with('status', 'account-deleted');
        } catch (ValidationException $e) {
            Log::warning('Failed account deletion attempt for user ID: ' . $request->user()->id);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Unexpected error during account deletion for user ID: ' . $request->user()->id . '. Error: ' . $e->getMessage());
            return back()->withErrors(['unexpected' => 'An unexpected error occurred. Please try again.']);
        }
    }
}