@extends('layouts.app')
@section('title', 'Edit Profile')
@section('content')
<div class="min-h-screen bg-navbar-bg flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <h2 class="mt-6 text-center text-3xl font-display font-bold text-logo-gold">
            Edit Your Profile
        </h2>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-black bg-opacity-50 py-8 px-4 shadow sm:rounded-lg sm:px-10 border border-logo-gold/30">
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PATCH')

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

                <div>
                    <label for="image_path" class="block text-sm font-medium text-subheading-gold">
                        Profile Picture
                    </label>
                    <input type="file" name="image_path" id="image_path" accept="image/*"
                           class="mt-1 block w-full text-menu-text"
                           onchange="previewImage(this);">
                    @error('image_path')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-subheading-gold">
                        Name
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                           class="mt-1 px-3 block w-full border border-logo-gold bg-black bg-opacity-50 rounded-md shadow-sm text-menu-text">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-subheading-gold">
                        Email
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                           class="mt-1 px-3 block w-full border border-logo-gold bg-black bg-opacity-50 rounded-md shadow-sm text-menu-text">
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="current_password" class="block text-sm font-medium text-subheading-gold">
                        Current Password (only if changing password)
                    </label>
                    <input type="password" name="current_password" id="current_password"
                           class="mt-1 px-3 block w-full border border-logo-gold bg-black bg-opacity-50 rounded-md shadow-sm text-menu-text">
                    @error('current_password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-subheading-gold">
                        New Password (leave blank if not changing)
                    </label>
                    <input type="password" name="password" id="password"
                           class="mt-1 px-3 block w-full border border-logo-gold bg-black bg-opacity-50 rounded-md shadow-sm text-menu-text">
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-subheading-gold">
                        Confirm New Password
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="mt-1 px-3 block w-full border border-logo-gold bg-black bg-opacity-50 rounded-md shadow-sm text-menu-text">
                </div>

                <div>
                    <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-button-text bg-logo-gold hover:bg-subheading-gold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-logo-gold">
                        Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var previewImg = document.getElementById('preview');
            previewImg.src = e.target.result;
            
            var mainImg = document.querySelector('img[alt="{{ $user->name }}\'s profile picture"]');
            if (mainImg) {
                mainImg.src = e.target.result;
            }
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection