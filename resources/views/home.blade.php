@extends('layouts.app')
@section('title', 'TimeQuest')
@section('content')

<style>
    html, body {
        margin: 0;
        padding: 0;
        overflow: hidden;
        height: 100%;
    }
    body > div {
        margin-top: 0;
        padding-top: 0;
    }
</style>

<div class="fixed inset-0 w-screen h-screen overflow-hidden">
    <img src="{{ asset('images/home_bg.jpg') }}" alt="Luxury Watches" class="object-cover w-full h-full fixed top-0 left-0 z-0">
    <div class="absolute inset-0 bg-black opacity-70 z-10"></div>
    <div class="absolute inset-0 flex flex-col items-center justify-center text-white px-4 z-20">

        @if (session('login_success'))
            <h1 class="text-5xl md:text-7xl font-display font-bold mb-2 text-center text-white tracking-wider">Login Successful! Welcome Back!</h1>
        @elseif (Auth::check()) 
            <h1 class="text-5xl md:text-7xl font-display font-bold mb-1 text-center text-white tracking-wider">TimeQuest</h1>
        @else
            <h1 class="text-5xl md:text-7xl font-display font-bold mb-2 text-center text-white tracking-wider">Welcome to TimeQuest</h1>
        @endif

        @if(session('status') === 'account-deleted')
            <script>
                alert('Your account has been successfully deleted.');
            </script>
        @endif

        <p class="text-2xl md:text-3xl font-serif mb-6 text-subheading-gold italic">Discover Timeless Elegance</p>
        @if(Auth::check() && auth()->user()->isAdmin())
            <a href="/admin/dashboard" class="bg-logo-gold text-navbar-bg py-3 px-8 rounded-full text-xl font-sans font-semibold hover:bg-subheading-gold transition duration-300 uppercase tracking-wide">
                Open Dashboard
            </a>
        @else
            <a href="/catalog" class="bg-logo-gold text-navbar-bg py-3 px-8 rounded-full text-xl font-sans font-semibold hover:bg-subheading-gold transition duration-300 uppercase tracking-wide">
                Explore Catalog
            </a>
        @endif

    </div>
</div>
@endsection
