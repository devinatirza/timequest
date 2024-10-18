@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-navbar-bg flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-5xl font-display font-bold text-logo-gold">
            Reset Your Password
        </h2>
        <p class="mt-2 text-center text-xl text-subheading-gold">
            Create a new, strong password for your account
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-black bg-opacity-50 py-8 px-4 shadow sm:rounded-lg sm:px-10 border border-logo-gold/30">
            <form class="space-y-6" action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="email" value="{{ $email ?? old('email') }}">

                <div>
                    <label for="password" class="block text-sm font-medium text-subheading-gold">
                        New Password
                    </label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" required
                               class="appearance-none block w-full px-3 py-2 border border-logo-gold bg-black bg-opacity-50 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-logo-gold focus:border-logo-gold sm:text-sm text-menu-text">
                    </div>
                    @if($errors->has('password'))
                        @foreach($errors->get('password') as $error)
                            <p class="mt-2 text-sm text-error-text">{{ $error }}</p>
                        @endforeach
                    @endif
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-subheading-gold">
                        Confirm New Password
                    </label>
                    <div class="mt-1">
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                               class="appearance-none block w-full px-3 py-2 border border-logo-gold bg-black bg-opacity-50 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-logo-gold focus:border-logo-gold sm:text-sm text-menu-text">
                    </div>
                </div>

                <div>
                    <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-button-text bg-logo-gold hover:bg-subheading-gold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-logo-gold transition duration-150 ease-in-out">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection