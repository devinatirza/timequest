<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;

class ForgotPasswordController extends Controller
{
    protected $maxAttempts = 5;
    protected $decayMinutes = 1;

    public function showForgotPasswordForm()
    {
        Log::info('Displaying forgot password form.');
        return view('auth.forgot-password');
    }

    public function verifyKBAAnswers(Request $request)
    {
        Log::info('Verifying KBA answers for password reset', ['email' => $request->email]);

        // Input validation
        $request->validate([
            'email' => 'required|email',
            'answer_1' => 'required|string|max:255',
            'answer_2' => 'required|string|max:255',
            'answer_3' => 'required|string|max:255',
        ]);

        $email = filter_var($request->email, FILTER_SANITIZE_EMAIL);
        Log::info('Sanitized email', ['email' => $email]);

        // Fetch user from the database
        $user = User::where('email', $email)->first();
        if (!$user) {
            Log::warning('User not found for email', ['email' => $email]);
            return back()->withErrors(['email' => __('The provided information is incorrect. Please check your input and try again.')]);
        }

        Log::info('User found', ['user_id' => $user->id]);

        // Check if account is locked
        if ($user->locked_until && $user->locked_until > now()) {
            $minutes = now()->diffInMinutes($user->locked_until);
            Log::warning('Account is locked', ['user_id' => $user->id, 'locked_until' => $user->locked_until]);
            return back()->withErrors(['email' => __('This account is locked. Please try again in :minutes minutes.', ['minutes' => $minutes])]);
        }

        $key = 'password_reset_' . $email;
        Log::info('Rate limiter key', ['key' => $key]);

        if (!$this->verifyKBAAnswersForUser($user, $request->only(['answer_1', 'answer_2', 'answer_3']))) {
            Log::warning('KBA answers verification failed', ['user_id' => $user->id]);

            RateLimiter::hit($key);
            $attemptsLeft = $this->maxAttempts - RateLimiter::attempts($key);
            Log::info('Attempts left', ['attempts_left' => $attemptsLeft]);

            if ($attemptsLeft <= 0) {
                $this->lockoutUser($email);
                Log::warning('User account locked due to too many attempts', ['user_id' => $user->id]);
                return back()->withErrors(['email' => __('Too many attempts. Your account has been locked for :minutes minutes.', ['minutes' => $this->decayMinutes])]);
            }
            return back()->withErrors(['email' => __('The provided information is incorrect. You have :attempts attempts left.', ['attempts' =>$attemptsLeft])]);
        }

        // Clear rate limiter after successful verification
        RateLimiter::clear($key);
        Log::info('KBA answers verified successfully', ['user_id' => $user->id]);

        // Generate password reset token
        // $token = $this->generateResetToken($user);
        // Log::info('Password reset token generated', ['user_id' => $user->id, 'token' => $token]);

        $token = $request->session() . Str::random(10);

        // Redirect to reset form
        return redirect()->route('password.reset', ['token' => $token, 'email' => $user->email]);
    }

    public function showResetForm(Request $request, $token)
    {
        Log::info('Displaying reset password form', ['token' => $token, 'email' => $request->email]);
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function reset(Request $request)
    {
        Log::info('Processing password reset', ['email' => $request->email]);

        // Input validation
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => [
                'required',
                'confirmed',
                'min:10',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{10,}$/',
                Rules\Password::defaults(),
            ],
        ]);

        // Fetch user from the database
        $email = filter_var($request->email, FILTER_SANITIZE_EMAIL);
        $user = User::where('email', $email)->first();
        if (!$user) {
            Log::warning('User not found during password reset', ['email' => $email]);
            return back()->withErrors(['email' => __('The provided email is incorrect.')]);
        }

        Log::info('User found for password reset', ['user_id' => $user->id]);

        // Reset the password with sal 
        $user->password = Hash::make($user->salt . $request->password);
        $user->reset_token = null;
        $user->reset_token_expires_at = null;
        $user->locked_until = null;
        $user->save();

        Log::info('Password successfully reset', ['user_id' => $user->id]);

        return redirect()->route('login')->with('status', __('Your password has been reset!'));
    }

    protected function verifyKBAAnswersForUser($user, $answers)
    {
        Log::info('Verifying KBA answers for user', ['user_id' => $user->id]);

        // Hash answers with salt
        $correctAnswer1 = hash('sha256', $user->salt . $user->answer_1);
        $correctAnswer2 = hash('sha256', $user->salt . $user->answer_2);
        $correctAnswer3 = hash('sha256', $user->salt . $user->answer_3);

        $providedAnswer1 = hash('sha256', $user->salt . filter_var($answers['answer_1'], FILTER_SANITIZE_STRING));
        $providedAnswer2 = hash('sha256', $user->salt . filter_var($answers['answer_2'], FILTER_SANITIZE_STRING));
        $providedAnswer3 = hash('sha256', $user->salt . filter_var($answers['answer_3'], FILTER_SANITIZE_STRING));

        Log::info('Security input', [
            'answer_1' => $user->answer_1,
            'filter_answer_1' => filter_var($answers['answer_1'], FILTER_SANITIZE_STRING)
        ]);
        // Logging the comparison for debugging
        Log::info('Comparing KBA answers', [
            'correct_answer_1' => $correctAnswer1,
            'provided_answer_1' => $providedAnswer1,
            'correct_answer_2' => $correctAnswer2,
            'provided_answer_2' => $providedAnswer2,
            'correct_answer_3' => $correctAnswer3,
            'provided_answer_3' => $providedAnswer3,
        ]);

        return hash_equals($user->answer_1, $providedAnswer1) &&
               hash_equals($user->answer_2, $providedAnswer2) &&
               hash_equals($user->answer_3, $providedAnswer3);
    }

    protected function generateResetToken($user)
    {
        $token = Str::random(10);
        $user->reset_token = Hash::make($token);
        $user->reset_token_expires_at = now()->addMinutes(30);
        $user->save();

        Log::info('Reset token stored for user', ['user_id' => $user->id]);

        return $token;
    }

    protected function getIpAddress()
    {
        $ip = request()->ip();
        Log::info('User IP address', ['ip' => $ip]);
        return $ip;
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
