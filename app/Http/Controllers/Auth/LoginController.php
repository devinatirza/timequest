<?php

namespace App\Http\Requests\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    protected $maxAttempts = 5;
    protected $decayMinutes = 15;

    public function showLoginForm()
    {
        return view('auth.login');
    }
}