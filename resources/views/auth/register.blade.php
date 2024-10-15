@extends('layouts.app')
@section('title', 'Register - TimeQuest')
@section('content')
<div class="min-h-screen bg-navbar-bg flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl w-full space-y-8 ">
        <div>
            <h2 class="text-center text-5xl font-display font-bold text-logo-gold">
                Join TimeQuest
            </h2>
            <p class="mt-2 text-center text-xl text-subheading-gold">
                Discover Timeless Elegance
            </p>
        </div>

        <form id="registerForm" class="mt-8 space-y-8" method="POST" action="{{ route('register') }}" enctype="multipart/form-data">
            @csrf

            <!-- Profile Picture Upload Section -->
            <div class="flex flex-col items-center mt-6 mb-8">
    <div class="w-32 h-32 rounded-full bg-logo-gold flex items-center justify-center overflow-hidden border-4 border-logo-gold shadow-lg">
        <img id="profile-preview" src="{{ old('image_path') ? asset('storage/' . old('image_path')) : asset('images/default-profile.jpg') }}" alt="Profile Picture" class="w-100 h-100 object-cover" width="150" height="150">
    </div>
    <label for="profile_image" class="mt-2 px-3 py-2 bg-logo-gold text-button-text text-sm rounded-full cursor-pointer hover:bg-subheading-gold transition duration-300 ease-in-out">
        Choose Profile Picture
    </label>
    <input id="profile_image" name="image_path" type="file" accept="image/jpeg,image/png" class="hidden" onchange="previewImage(event)">
    @error('image_path')
        <p class="text-error-text text-sm mt-1">{{ $message }}</p>
    @enderror
</div>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                <!-- Left Column -->
                <div class="space-y-6">
                    <div>
                        <label for="name" class="block text-2sm font-display text-subheading-gold">Name</label>
                        <input id="name" name="name" type="text" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-logo-gold bg-white placeholder-gray-400 text-text-brown focus:outline-none focus:ring-2 focus:ring-logo-gold focus:border-transparent focus:z-10 sm:text-sm transition duration-300 ease-in-out" placeholder="Your Full Name" value="{{old("name")}}">
                        @error('name')
                            <p class="text-error-text text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="block text-2sm font-display text-subheading-gold">Email address</label>
                        <input id="email" name="email" type="email" autocomplete="email" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-logo-gold bg-white placeholder-gray-400 text-text-brown focus:outline-none focus:ring-2 focus:ring-logo-gold focus:border-transparent focus:z-10 sm:text-sm transition duration-300 ease-in-out" placeholder="you@example.com" value="{{old("email")}}">
                        @error('email')
                            <p class="text-error-text text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="block text-2sm font-display text-subheading-gold">Password</label>
                        <input id="password" name="password" type="password" autocomplete="new-password" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-logo-gold bg-white placeholder-gray-400 text-text-brown focus:outline-none focus:ring-2 focus:ring-logo-gold focus:border-transparent focus:z-10 sm:text-sm transition duration-300 ease-in-out" placeholder="••••••••">
                        @error('password')
                            <p class="text-error-text text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-2sm font-display text-subheading-gold">Confirm Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-logo-gold bg-white placeholder-gray-400 text-text-brown focus:outline-none focus:ring-2 focus:ring-logo-gold focus:border-transparent focus:z-10 sm:text-sm transition duration-300 ease-in-out" placeholder="••••••••">
                    </div>
                </div>
                
                <!-- Right Column - Security Questions -->
                <div class="space-y-6">
                    @for ($i = 1; $i <= 3; $i++)
                        <div>
                            <label for="answer_{{ $i }}" class="block text-2sm font-display text-subheading-gold">{{ $kbaQuestions[$i] }}</label>
                            <input id="answer_{{ $i }}" name="answer_{{ $i }}" type="text" required class="appearance-none rounded-md relative block w-full px-3 py-2 border border-logo-gold bg-white placeholder-gray-400 text-text-brown focus:outline-none focus:ring-2 focus:ring-logo-gold focus:border-transparent focus:z-10 sm:text-sm transition duration-300 ease-in-out" placeholder="Your answer" value="{{old("answer_" . $i)}}">
                            @error("answer_$i")
                                <p class="text-error-text text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endfor
                </div>
            </div>

            <div class="mt-8 w-full flex flex-col items-center">
                <button id="registerButton" type="submit" class="group relative py-3 px-4 border text-lg font-medium rounded-full text-button-text bg-logo-gold hover:bg-subheading-gold w-full md:w-1/2 transition duration-300 ease-in-out">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <svg class="h-5 w-5 text-button-text group-hover:text-white" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    Register
                </button>
            </div>
        </form>

        <div class="text-center mt-4">
            <a href="{{ route('login') }}" class="font-display text-sm text-subheading-gold hover:text-logo-gold transition duration-150 ease-in-out">
                Already have an account? Sign in
            </a>
        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('profile-preview');
        output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
}

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('registerForm');
    const button = document.getElementById('registerButton');
    const tooltip = document.getElementById('tooltipText');
    const inputs = form.querySelectorAll('input[required]');

    function checkFormValidity() {
        let isValid = true;
        inputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
            }
        });

        button.disabled = !isValid;
        button.classList.toggle('opacity-50', !isValid);
        button.classList.toggle('cursor-not-allowed', !isValid);

        if (isValid) {
            button.classList.remove('text-gray-400', 'bg-gray-600');
            button.classList.add('text-button-text', 'bg-gradient-to-r', 'from-logo-gold', 'to-subheading-gold', 'hover:from-subheading-gold', 'hover:to-logo-gold');
        } else {
            button.classList.add('text-gray-400', 'bg-gray-600');
            button.classList.remove('text-button-text', 'bg-gradient-to-r', 'from-logo-gold', 'to-subheading-gold', 'hover:from-subheading-gold', 'hover:to-logo-gold');
        }
    }

    inputs.forEach(input => {
        input.addEventListener('input', checkFormValidity);
    });

    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            let emptyFields = [];
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    emptyFields.push(input.previousElementSibling.textContent.trim());
                }
            });
            tooltip.textContent = `Please fill in the following fields correctly: ${emptyFields.join(', ')}`;
            tooltip.classList.remove('hidden');
            setTimeout(() => {
                tooltip.classList.add('hidden');
            }, 5000);
        }
    });
});
</script>
@endsection
