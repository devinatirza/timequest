<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'throttle:6,1']);
    }

    public function edit(Request $request): View
    {
        $user = $request->user();
        return view('profile.edit', compact('user'));
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        $validated = $request->validated();
        
        $safeFields = array_diff_key($validated, array_flip(['password', 'email']));
        
        $user->fill($safeFields);

        if ($request->has('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        Log::info('Profile updated for user ID: ' . $user->id);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
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