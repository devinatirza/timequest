@extends('layouts.app')

@section('title', 'TimeQuest')

@section('content')
<div class="relative h-screen">
    <img src="{{ asset('images/home_bg.jpg') }}" alt="Luxury Watches" class="w-full h-full overflow-hidden absolute">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    <div class="absolute inset-0 flex flex-col items-center justify-center text-white">
        <h1 class="text-4xl md:text-6xl font-bold mb-4">Welcome to TimeQuest</h1>
        <p class="text-xl md:text-2xl mb-8">Discover Timeless Elegance</p>
        <a href="/catalog" class="bg-white text-black py-2 px-6 rounded-full text-lg font-semibold hover:bg-gray-200 transition duration-300">
            Explore Catalog
        </a>
    </div>
</div>
@endsection
