@extends('layouts.app')
@section('title', 'Contact TimeQuest - Luxury Timepieces')
@section('content')
<div class="bg-navbar-bg min-h-screen py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-5xl font-display font-bold text-logo-gold text-center mb-12 tracking-wider">Contact Us</h1>
        
        <div class="bg-black bg-opacity-60 p-8 rounded-lg shadow-2xl border border-logo-gold/30 mb-12 text-center">
            <p class="text-2xl font-serif text-menu-text leading-relaxed mb-8">
                We invite you to experience the world of TimeQuest. Our luxury boutique awaits your visit, where our expert staff is ready to guide you through our exquisite collection.
            </p>
        </div>

        <div class="grid md:grid-cols-2 gap-8 mb-12">
            <div class="bg-black bg-opacity-60 p-8 rounded-lg shadow-xl border border-logo-gold/30">
                <h3 class="text-3xl font-display font-bold text-subheading-gold mb-6">Contact Information</h3>
                <ul class="space-y-4 font-serif text-xl text-menu-text">
                    <li>Phone: +1 (555) 123-4567</li>
                    <li>Email: info@timequest.com</li>
                    <li>
                        <a href="https://wa.me/15551234567" target="_blank" rel="noopener noreferrer" class="text-logo-gold hover:text-subheading-gold transition duration-300">
                            Contact via WhatsApp
                        </a>
                    </li>
                </ul>
            </div>

            <div class="bg-black bg-opacity-60 p-8 rounded-lg shadow-xl border border-logo-gold/30">
                <h3 class="text-3xl font-display font-bold text-subheading-gold mb-6">Visit Our Boutique</h3>
                <p class="font-serif text-xl text-menu-text mb-4">123 Luxury Lane, Timepiece City, TC 12345</p>
                <p class="font-serif text-xl text-menu-text">
                    Monday - Friday: 10AM - 7PM<br>
                    Saturday: 11AM - 6PM<br>
                    Sunday: Closed
                </p>
            </div>
        </div>

        <div class="grid md:grid-cols-2 gap-8">
            <div>
                <img src="{{ asset('images/store-frontview.jpg') }}" alt="TimeQuest Boutique Front View" class="w-full h-64 object-cover rounded-lg shadow-lg">
                <p class="text-center text-menu-text mt-2 font-serif italic">Our Luxurious Storefront</p>
            </div>
            <div>
                <img src="{{ asset('images/store.jpg') }}" alt="TimeQuest Boutique Interior" class="w-full h-64 object-cover rounded-lg shadow-lg">
                <p class="text-center text-menu-text mt-2 font-serif italic">Elegant Interior Display</p>
            </div>
        </div>
    </div>
</div>
@endsection