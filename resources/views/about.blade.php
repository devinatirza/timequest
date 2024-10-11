@extends('layouts.app')
@section('title', 'About')
@section('content')
<div class="bg-navbar-bg min-h-screen py-16 px-4 sm:px-6 lg:px-8 font-sans">
    <div class="max-w-5xl mx-auto">
        <h1 class="text-6xl font-display font-bold text-logo-gold text-center mb-16 tracking-wide">About TimeQuest</h1>
        
        <div class="bg-black bg-opacity-40 p-10 rounded-lg shadow-2xl mb-16 border border-logo-gold/20">
            <p class="text-2xl font-serif text-menu-text leading-relaxed mb-8">
                At TimeQuest, we specialize in offering a curated collection of luxury timepieces that blend tradition, precision, and elegance. Our passion for horology drives us to provide our customers with only the finest watches from renowned brands.
            </p>
            <div class="w-1/4 h-px bg-logo-gold mx-auto"></div>
        </div>

        <div class="grid md:grid-cols-2 gap-12 mb-16">
            <div class="bg-black bg-opacity-40 p-8 rounded-lg shadow-xl border border-logo-gold/20">
                <h3 class="text-2xl font-display font-bold text-subheading-gold mb-6">Our Vision</h3>
                <p class="text-xl font-serif text-menu-text leading-relaxed">
                    We believe that every watch tells a story. Our mission is to offer timeless pieces that reflect both craftsmanship and innovation. Each timepiece is selected not only for its precision but for the legacy it represents.
                </p>
            </div>
            <div class="bg-black bg-opacity-40 p-8 rounded-lg shadow-xl border border-logo-gold/20">
                <h3 class="text-2xl font-display font-bold text-subheading-gold mb-6">Commitment to Excellence</h3>
                <p class="text-xl font-serif text-menu-text leading-relaxed">
                    From the moment you enter TimeQuest, you embark on a journey through time, discovering the intricacies of exceptional watchmaking. We aim to provide a seamless shopping experience and unparalleled customer service to ensure you find the perfect piece.
                </p>
            </div>
        </div>

        <div class="bg-black bg-opacity-40 p-10 rounded-lg shadow-2xl border border-logo-gold/20">
            <h3 class="text-3xl font-display font-bold text-logo-gold mb-10 text-center">Why Choose TimeQuest?</h3>
            <ul class="space-y-6 text-2xl font-serif text-menu-text mb-10">
                @foreach(['Handpicked selection of luxury watches', 'Trusted by watch enthusiasts and collectors', 'Exclusive timepieces from iconic brands', 'Personalized customer support'] as $reason)
                    <li class="flex items-center">
                        <span class="text-logo-gold mr-4 text-xl">✦</span>
                        <span>{{ $reason }}</span>
                    </li>
                @endforeach
            </ul>
            <p class="text-xl font-serif italic text-menu-text leading-relaxed text-center mt-10 font-light">
                Thank you for choosing TimeQuest. We invite you to explore our collection and discover timeless elegance with us.
            </p>
        </div>
    </div>
</div>
@endsection