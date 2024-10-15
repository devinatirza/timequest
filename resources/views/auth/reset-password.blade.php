<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <form method="POST" action="{{ route('password.reset.kba') }}">
            @csrf

            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" name="question_number" value="{{ $question_number }}">

            <div class="mb-4">
                <x-label for="kba_answer" :value="__('Security Question')" />
                <p class="mt-1 text-sm text-gray-600">{{ $question }}</p>
                <x-input id="kba_answer" class="block mt-1 w-full" type="text" name="kba_answer" required autofocus />
            </div>

            <div class="mt-4">
                <x-label for="password" :value="__('New Password')" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" :value="__('Confirm New Password')" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('Reset Password') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>