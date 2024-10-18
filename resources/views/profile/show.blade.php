@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-navbar-bg flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-3xl font-display font-bold text-logo-gold">
            Your Profile
        </h2>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-black bg-opacity-50 py-8 px-4 shadow sm:rounded-lg sm:px-10 border border-logo-gold/30">
            <div class="flex justify-center mb-6">
                <img id="preview" src="{{ $user->image_path 
                    ? route('user.image', [
                        'userId' => $user->id, 
                        'filename' => $user->image_path ? basename($user->image_path) : null,
                        'v' => time() 
                    ]) 
                    : asset('images/default-profile.jpg') }}" 
                alt="{{ $user->name }}'s profile picture"
                class="w-32 h-32 rounded-full border-2 border-logo-gold">
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-subheading-gold">Name</label>
                <p class="mt-1 text-sm text-menu-text">{{ $user->name }}</p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-subheading-gold">Email</label>
                <p class="mt-1 text-sm text-menu-text">{{ $user->email }}</p>
            </div>

            <div class="mt-6">
                <a href="{{ route('profile.edit') }}" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-button-text bg-logo-gold hover:bg-subheading-gold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-logo-gold">
                    Edit Profile
                </a>
            </div>
            <div class="mt-4">
                <a href="{{ route('logout') }}" 
                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
@endsection