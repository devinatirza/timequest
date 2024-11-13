<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Constants\KbaQuestions;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Validation\ValidationException;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register', ['kbaQuestions' => KbaQuestions::QUESTIONS]);
    }

    public function store(Request $request): RedirectResponse
    {
        $throttleKey = Str::lower($request->ip()) . '|' . Str::lower($request->input('email'));

        if (RateLimiter::tooManyAttempts($throttleKey, 6)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            throw ValidationException::withMessages([
                'email' => "Too many attempts. Please try again in $seconds seconds.",
            ]);
        }
        RateLimiter::hit($throttleKey, 60);
        
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'min:3', 'max:255', 'regex:/^[\pL\s\-]+$/u'],
                'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:'.User::class],
                'password' => [
                    'required', 
                    'confirmed', 
                    'min:10',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{10,}$/',
                    Rules\Password::defaults(),
                ],
                'answer_1' => ['required', 'string', 'min:3', 'max:255'],
                'answer_2' => ['required', 'string', 'min:3', 'max:255'],
                'answer_3' => ['required', 'string', 'min:3', 'max:255'],
                'image_path' => ['nullable', 'file', 'mimes:jpeg,png,jpg', 'max:2048'],
            ], [
                'name.required' => 'Name is required.',
                'name.min' => 'Name must contain at least 3 characters.',
                'name.max' => 'Name cannot exceed 255 characters.',
                'name.regex' => 'Name can only contain letters and spaces.',
                'email.required' => 'Email address is required.',
                'email.email' => 'Please enter a valid email address.',
                'email.unique' => 'This email is already registered.',
                'password.required' => 'Password is required.',
                'password.confirmed' => 'Password confirmation does not match.',
                'password.min' => 'Password must be at least 10 characters long.',
                'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special characterfrom the set: @, $, !, %, *, ?, &.',
                'answer_1.required' => 'Security answer 1 is required.',
                'answer_1.min' => 'Security answer 1 must be at least 3 characters long.',
                'answer_2.required' => 'Security answer 2 is required.',
                'answer_2.min' => 'Security answer 2 must be at least 3 characters long.',
                'answer_3.required' => 'Security answer 3 is required.',
                'answer_3.min' => 'Security answer 3 must be at least 3 characters long.',
                'image_path.image' => 'The profile picture must be an image.',
                'image_path.mimes' => 'The profile picture must be a valid image file (jpeg, jpg, png)!',
                'image_path.max' => 'The profile picture cannot exceed 2MB in size.',
            ]);

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
                        Log::error('Invalid image type: ' . $uploadedFile->getClientMimeType());
                        throw ValidationException::withMessages([
                            'image_path' => ['The uploaded file type is not allowed.']
                        ]);
                    }

                    $manager = new ImageManager(new Driver());
                    $image = $manager->read($tempPath);

                    $imageName = Str::uuid() . '.png';
                    $path = storage_path('app/private/user_profiles/' . $imageName);

                    $image->cover(300, 300)
                        ->toPng()
                        ->save($path);

                    $imagePath = 'user_profiles/' . $imageName;

                    if (!exif_imagetype($path)) {
                        unlink($path);
                        throw ValidationException::withMessages([
                            'image_path' => ['The uploaded file could not be processed as an image.']
                        ]);
                    }

                } catch (ValidationException $e) {
                    throw $e;
                } catch (\Exception $e) {
                    Log::error('Image processing failed: ' . $e->getMessage());
                    throw ValidationException::withMessages([
                        'image_path' => ['An error occurred while processing the image. Registration blocked for security reasons.']
                    ]);
                }
            } else {
                $imagePath = null;
            }

            $sanitized = [
                'id' => Str::uuid()->toString(),
                'name' => strip_tags($validated['name']),
                'email' => filter_var($validated['email'], FILTER_SANITIZE_EMAIL),
                'password' => $validated['password'], 
                'answer_1' => strip_tags($validated['answer_1']),
                'answer_2' => strip_tags($validated['answer_2']),
                'answer_3' => strip_tags($validated['answer_3']),
            ];

            $salt = Str::random(32);
            $hashedPassword = Hash::make($salt . $sanitized['password']);
            
            $user = User::create([
                'id' => $sanitized['id'],
                'name' => $sanitized['name'],
                'email' => $sanitized['email'],
                'password' => $hashedPassword,
                'salt' => $salt,
                'answer_1' => hash('sha256', $salt . $sanitized['answer_1']),
                'answer_2' => hash('sha256', $salt . $sanitized['answer_2']),
                'answer_3' => hash('sha256', $salt . $sanitized['answer_3']),
                'image_path' => $imagePath,
            ]);

            event(new Registered($user));

            Auth::login($user);

            RateLimiter::clear($throttleKey);

            return redirect('login');

        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput($request->except('password', 'password_confirmation'));
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error during registration: ' . $e->getMessage());
            return back()->withErrors(['database' => 'A database error occurred. Please try again.'])->withInput($request->except('password', 'password_confirmation'));
        } catch (\Exception $e) {
            Log::error('Unexpected error during registration: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->withErrors(['unexpected' => 'An unexpected error occurred. Please try again.'])->withInput($request->except('password', 'password_confirmation'));
        }
    }

}