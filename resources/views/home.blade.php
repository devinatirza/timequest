@extends('layouts.app')
@section('title', 'TimeQuest')
@section('content')
<div class="relative h-screen">
    <img src="{{ asset('images/home_bg.jpg') }}" alt="Luxury Watches" class="w-full h-full object-cover absolute">
    <div class="absolute inset-0 bg-black opacity-70"></div>
    <div class="absolute inset-0 flex flex-col items-center justify-center text-white px-4">
        <h1 class="text-5xl md:text-7xl font-display font-bold mb-2 text-center text-white tracking-wider">Welcome to TimeQuest</h1>
        <p class="text-2xl md:text-3xl font-serif mb-12 text-subheading-gold italic">Discover Timeless Elegance</p>
        <a href="/catalog" class="bg-logo-gold text-navbar-bg py-3 px-8 rounded-full text-xl font-sans font-semibold hover:bg-subheading-gold transition duration-300 uppercase tracking-wide">
            Explore Catalog
        </a>
    </div>
</div>
@endsection