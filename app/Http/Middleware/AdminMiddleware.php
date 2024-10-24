<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Notifications\TwoFactorCodeNotification;
use Illuminate\Support\Facades\RateLimiter;

class AdminMiddleware
{
    private const MAX_ATTEMPTS = 5;
    private const BLOCK_DURATION = 1800;

    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        
        $key = 'admin-access:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, self::MAX_ATTEMPTS)) {
            $seconds = RateLimiter::availableIn($key);
            Log::warning('Rate limit exceeded for admin access', [
                'ip' => $request->ip(),
                'user_id' => $user->id ?? null,
                'blocked_for_seconds' => $seconds
            ]);
            
            return redirect()->route('login')
                ->with('error', "Too many attempts. Please try again in " . ceil($seconds / 60) . " minutes.");
        }
        
        if (!$user || !$user->is_admin) {
            RateLimiter::hit($key, self::BLOCK_DURATION);
            
            Log::warning('Non-admin user attempted to access admin area', [
                'ip' => $request->ip(),
                'user_id' => $user->id ?? null,
                'user_agent' => $request->userAgent(),
                'url' => $request->fullUrl(),
                'timestamp' => Carbon::now()->toIso8601String()
            ]);
            
            return redirect()->route('login')
                ->with('error', 'Unauthorized access.');
        }

        $lastActivity = session('last_activity');
        $timeout = config('session.lifetime') * 60;
        
        if ($lastActivity && Carbon::now()->diffInSeconds(Carbon::parse($lastActivity)) > $timeout) {
            Auth::logout();
            session()->flush();
            
            Log::info('Admin session timed out', [
                'user_id' => $user->id,
                'ip' => $request->ip(),
                'last_activity' => $lastActivity
            ]);
            
            return redirect()->route('login')
                ->with('error', 'Session expired. Please login again.');
        }

        RateLimiter::clear($key);
        
        session(['last_activity' => Carbon::now()]);
        
        return $next($request);
    }
}