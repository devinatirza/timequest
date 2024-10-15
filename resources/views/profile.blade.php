@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-navbar-bg py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="text-center text-3xl font-display font-bold text-logo-gold mb-8">
            Your Profile
        </h2>

        <div class="bg-black bg-opacity-50 overflow-hidden shadow-xl sm:rounded-lg border border-logo-gold/30">
            <div class="p-6 sm:px-20 bg-black bg-opacity-50 border-b border-logo-gold/30">
                <div class="flex items-center">
                    <div class="w-24 h-24 rounded-full overflow-hidden border-2 border-logo-gold">
                        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    </div>
                    <div class="ml-4 text-lg text-subheading-gold font-semibold">
                        {{ $user->name }}
                    </div>
                </div>
            </div>

            <div class="bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 p-6">
                <div>
                    <div class="text-lg font-medium text-logo-gold mb-2">Email</div>
                    <div class="text-menu-text">{{ $user->email }}</div>
                </div>
                <div>
                    <div class="text-lg font-medium text-logo-gold mb-2">Member Since</div>
                    <div class="text-menu-text">{{ $user->created_at->format('F d, Y') }}</div>
                </div>
            </div>

            <div class="p-6 bg-black bg-opacity-50 border-t border-logo-gold/30">
                <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="name" class="block text-sm font-medium text-subheading-gold">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required 
                               class="mt-1 block w-full border-logo-gold bg-black bg-opacity-50 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-logo-gold focus:border-logo-gold sm:text-sm text-menu-text">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-subheading-gold">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required 
                               class="mt-1 block w-full border-logo-gold bg-black bg-opacity-50 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-logo-gold focus:border-logo-gold sm:text-sm text-menu-text">
                    </div>

                    <div>
                        <button type="submit" 
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-button-text bg-logo-gold hover:bg-subheading-gold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-logo-gold">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection