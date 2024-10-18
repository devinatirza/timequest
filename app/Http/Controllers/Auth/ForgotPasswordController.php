<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    protected $maxAttempts = 5;
    protected $decayMinutes = 15;
    protected $tokenExpiration = 3;

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function verifyKBAAnswers(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'answer_1' => 'required|string|max:255',
            'answer_2' => 'required|string|max:255',
            'answer_3' => 'required|string|max:255',
        ]);

        $email = filter_var($request->email, FILTER_SANITIZE_EMAIL);

        $user = User::where('email', $email)->first();
        if (!$user) {
            return back()->withErrors(['email' => __('The provided information is incorrect. Please check your input and try again.')]);
        }

        if ($user->locked_until && $user->locked_until > now()) {
            $minutes = now()->diffInMinutes($user->locked_until);
            return back()->withErrors(['email' => __('This account is locked. Please try again in :minutes minutes.', ['minutes' => $minutes])]);
        }

        $key = 'password_reset_' . $email;

        if (!$this->verifyKBAAnswersForUser($user, $request->only(['answer_1', 'answer_2', 'answer_3']))) {
            Log::warning('KBA answers verification failed', ['user_id' => $user->id]);

            RateLimiter::hit($key);
            $attemptsLeft = $this->maxAttempts - RateLimiter::attempts($key);

            if ($attemptsLeft <= 0) {
                $this->lockoutUser($email);
                Log::warning('User account locked due to too many attempts', ['user_id' => $user->id]);
                return back()->withErrors(['email' => __('Too many attempts. Your account has been locked for :minutes minutes.', ['minutes' => $this->decayMinutes])]);
            }
            return back()->withErrors(['email' => __('The provided information is incorrect. You have :attempts attempts left.', ['attempts' =>$attemptsLeft])]);
        }

        RateLimiter::clear($key);

        $token = $this->generateResetToken($user->id, $user->salt);

        return redirect()->route('password.reset')->withCookie($token)->with('email', $user->email);
    }

    public function showResetForm(Request $request)
    {
        $token = $request->cookie('password_reset_token');
        $email = $request->session()->get('email');

        if (!$token || !$email) {
            Log::warning('Invalid reset attempt', ['ip' => $request->ip()]);
            return redirect('/');
        }

        return view('auth.reset-password', ['token' => $token, 'email' => $email]);
    }

    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => [
                'required', 
                'confirmed', 
                'min:10',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{10,}$/',
            ],
        ], [
            'password.required' => 'Password is required.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 10 characters long.',
            'password.regex' => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one special character.',
        ]);

        if ($validator->fails()) {
            return view('auth.reset-password', ['email' => $request->email])->withErrors($validator)->withInput($request->except('password', 'password_confirmation'));
        }

        try {
            $email = $request->email;
            $token = $request->cookie('password_reset_token');

            $user = User::where('email', $email)->first();
            if (!$user) {
                throw ValidationException::withMessages(['email' => 'The provided email is incorrect.']);
            }

            if (!$token || !$this->validateResetToken($token, $user->id, $user->salt)) {
                Log::warning('Invalid token used for password reset', ['ip' => $request->ip()]);
                throw ValidationException::withMessages(['email' => 'Invalid or expired token used for password reset.']);
            }

            $user->password = Hash::make($user->salt . $request->password);
            $user->locked_until = null;
            $user->save();

            Log::info('Password successfully reset', ['user_id' => $user->id]);

            Cookie::queue(Cookie::forget('password_reset_token'));

            return redirect()->route('login')->with('status', 'Your password has been reset!');

        } catch (ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput($request->except('password', 'password_confirmation'));
        } catch (\Exception $e) {
            Log::error('Unexpected error during password reset: ' . $e->getMessage());
            return back()
                ->withErrors(['unexpected' => 'An unexpected error occurred. Please try again.'])
                ->withInput($request->except('password', 'password_confirmation'));
        }
    }  

    protected function verifyKBAAnswersForUser($user, $answers)
    {
        $providedAnswer1 = hash('sha256', $user->salt . filter_var($answers['answer_1'], FILTER_SANITIZE_STRING));
        $providedAnswer2 = hash('sha256', $user->salt . filter_var($answers['answer_2'], FILTER_SANITIZE_STRING));
        $providedAnswer3 = hash('sha256', $user->salt . filter_var($answers['answer_3'], FILTER_SANITIZE_STRING));

        return hash_equals($user->answer_1, $providedAnswer1) &&
               hash_equals($user->answer_2, $providedAnswer2) &&
               hash_equals($user->answer_3, $providedAnswer3);
    }

    protected function generateResetToken($userId, $salt)
    {
        $token = Hash::make($salt . $userId);
        $encryptedToken = encrypt($token);
        
        return cookie('password_reset_token', $encryptedToken, $this->tokenExpiration, null, null, true, true);
    }

    protected function validateResetToken($encryptedToken, $id, $salt)
    {
        try {
            $token = decrypt($encryptedToken);
            return Hash::check($salt . $id, $token);
        } catch (\Exception $e) {
            return false;
        }
    }

    protected function lockoutUser($email)
    {
        $user = User::where('email', $email)->first();

        if ($user) {
            $user->locked_until = now()->addMinutes($this->decayMinutes);
            $user->save();
            Log::warning('User account locked', ['user_id' => $user->id, 'locked_until' => $user->locked_until]);
        }
    }
}
