@extends('layouts.app')
@section('title', 'Sign In - TimeQuest')
@section('content')
<div class="min-h-screen bg-navbar-bg flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-xl w-full space-y-8 ">
        <div>
            <h2 class="text-center text-5xl font-display font-bold text-logo-gold">
                Welcome to TimeQuest
            </h2>
            <p class="mt-2 text-center text-xl text-subheading-gold">
                Discover Timeless Elegance
            </p>
        </div>

    <form class="space-y-6" action="{{ route('login') }}" method="POST">
        @csrf
        <div class="flex flex-col">
            <label for="email" class="block text-sm font-medium text-subheading-gold">
                Email address
            </label>
            <div class="mt-1">
                <input id="email" name="email" type="email" autocomplete="email" required
                        class="appearance-none block w-full px-3 py-2 border border-logo-gold bg-black bg-opacity-50 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-logo-gold focus:border-logo-gold sm:text-sm text-menu-text"
                        value="{{ old('email') }}">
            </div>
            @error('email')
                <p class="mt-2 text-sm text-error-text">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="block text-sm font-medium text-subheading-gold">
                Password
            </label>
            <div class="mt-1">
                <input id="password" name="password" type="password" autocomplete="current-password" required
                        class="appearance-none block w-full px-3 py-2 border border-logo-gold bg-black bg-opacity-50 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-logo-gold focus:border-logo-gold sm:text-sm text-menu-text">
            </div>
            @error('password')
                <p class="mt-2 text-sm text-error-text">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember_me" name="remember" type="checkbox"
                        class="h-4 w-4 text-logo-gold focus:ring-logo-gold border-logo-gold rounded">
                <label for="remember_me" class="ml-2 block text-sm text-subheading-gold">
                    Remember me
                </label>
            </div>
            <div class="text-sm">
                <a href="{{ route('password.request') }}" class="font-medium text-logo-gold hover:text-subheading-gold">
                    Forgot your password?
                </a>
            </div>
        </div>

        <div>
            <button type="submit"
                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-button-text bg-logo-gold hover:bg-subheading-gold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-logo-gold transition duration-150 ease-in-out">
                Sign in
            </button>
        </div>
    </form>

    <div class="mt-2">
        <div class="relative">
            <div class="relative flex justify-center text-sm">
                <a href="{{ route('register') }}"
                    class="px-2 bg-black bg-opacity-50 text-text-white">
                    Didn't have an account? Register Now
                </a>
            </div>
        </div>
    </div>
</div>
@endsection