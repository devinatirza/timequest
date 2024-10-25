<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class LoginRequest extends FormRequest
{
    protected $maxAttempts = 5;
    protected $decayMinutes = 15;

    public function authorize(): bool
    {
        return true;
    }

    protected function findUser(): ?User
    {
        $email = $this->sanitizeInput($this->input('email'));
        $user = User::where('email', $email)->first();

        return $user;
    }

    public function authenticate(): void
    {

        $user = $this->findUser();

        if (!$user) {
            $this->failedLoginResponse('auth.failed');
        }

        if ($user->locked_until && $user->locked_until > now()) {
            throw ValidationException::withMessages([
                'email' => __('auth.locked', ['minutes' => now()->diffInMinutes($user->locked_until)]),
            ]);
        }

        if (!$this->checkPassword($user, $this->input('password'))) {
            $this->failedLoginResponse('auth.failed');
        }

        $this->loginUser($user);
        $this->handlePostLoginRedirect($user);
    }

    protected function checkPassword(User $user, string $password): bool
    {
        $saltedPassword = $user->salt . $password;
        return Hash::check($saltedPassword, $user->password);
    }

    protected function loginUser(User $user): void
    {
        Auth::login($user, $this->boolean('remember'));
        RateLimiter::clear($this->throttleKey());
        $this->session()->regenerate();

        Log::info('Successful login', ['user_id' => $user->id, 'ip' => $this->ip()]);
    }

    protected function failedLoginResponse(string $messageKey): void
    {
        RateLimiter::hit($this->throttleKey());
        
        if (RateLimiter::attempts($this->throttleKey()) >= $this->maxAttempts) {
            $this->lockoutUser();
        }

        Log::warning('Login failed', [
            'email' => $this->input('email'),
            'reason' => $messageKey,
            'ip' => $this->ip()
        ]);

        throw ValidationException::withMessages([
            'email' => __($messageKey),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('email')).'|'.$this->ip());
    }

    protected function sanitizeInput($input): string
    {
        return htmlspecialchars(strip_tags($input), ENT_QUOTES, 'UTF-8');
    }

    protected function lockoutUser(): void
    {
        $email = $this->input('email');
        $user = User::where('email', $email)->first();

        if ($user) {
            $user->locked_until = now()->addMinutes($this->decayMinutes);
            $user->save();
            Log::warning('User account locked', ['user_id' => $user->id, 'ip' => $this->ip()]);
        }
    }

    protected function handlePostLoginRedirect(User $user): void
    {
        if ($user->isAdmin()){
            redirect()->intended(route('admin.dashboard'));
        } else {
            Session::flash('login_success', 'Login successful! Welcome back.');
            redirect()->intended(route('home'));
        }
    }

}