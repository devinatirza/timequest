@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-navbar-bg flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="mx-auto w-full max-w-md">
        <h2 class="mt-6 text-center text-5xl font-display font-bold text-logo-gold">
            Forgot Your Password?
        </h2>
        <p class="mt-2 text-center text-xl text-subheading-gold">
            Enter your email and answer security questions to reset your password
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-black bg-opacity-50 py-8 px-4 shadow sm:rounded-lg sm:px-10 border border-logo-gold/30">
            <form class="space-y-6" action="{{ route('password.kba.verify') }}" method="POST">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-subheading-gold">
                        Email address
                    </label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required
                               class="appearance-none block w-full px-3 py-2 border border-logo-gold bg-black bg-opacity-50 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-logo-gold focus:border-logo-gold sm:text-sm text-menu-text"
                               value="{{ old('email') }}">
                    </div>
                </div>

                <div class="space-y-4">
                    <h3 class="text-lg font-medium text-logo-gold">Security Questions</h3>
                    @foreach(App\Constants\KbaQuestions::QUESTIONS as $key => $question)
                        <div>
                            <label for="answer_{{ $loop->iteration }}" class="block text-sm font-medium text-subheading-gold">
                                {{ $question }}
                            </label>
                            <div class="mt-1">
                                <input id="answer_{{ $loop->iteration }}" name="answer_{{ $loop->iteration }}" type="text" autocomplete="off" required class="appearance-none block w-full px-3 py-2 border border-logo-gold bg-black bg-opacity-50 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-logo-gold focus:border-logo-gold sm:text-sm text-menu-text">
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($errors->any())
                    <div class="flex">
                        <h3 class="text-sm font-medium text-error-text">{{ $errors->first() }}</d>
                    </div>
                @endif

                <div>
                    <button type="submit"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-button-text bg-logo-gold hover:bg-subheading-gold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-logo-gold transition duration-150 ease-in-out">
                        Verify
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection