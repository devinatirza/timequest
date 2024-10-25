@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-navbar-bg py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-5xl font-display font-bold text-logo-gold text-center mb-12">Exquisite Timepieces</h1>
        
        <div class="mb-8 flex justify-center">
            <a href="{{ route('catalog') }}" class="w-auto sm:w-1/4 lg:w-1/6 px-2 flex">
                <div class="w-full py-4 border-2 border-logo-gold text-white bg-subheading-gold rounded-full text-center cursor-pointer transition-all duration-300 flex items-center justify-center {{ request('brand') == '' ? 'bg-logo-gold text-black border-subheading-gold' : 'hover:bg-logo-gold hover:text-black' }}">
                    All Products
                </div>
            </a>
            @foreach($brands as $brand)
            <a href="{{ route('catalog', ['brand' => $brand->id]) }}" class="w-auto sm:w-1/4 lg:w-1/6 px-2 flex">
                <div class="w-full py-4 border-2 border-logo-gold text-white bg-subheading-gold rounded-full text-center cursor-pointer transition-all duration-300 flex items-center justify-center {{ request('brand') == $brand->id ? 'bg-logo-gold text-black border-subheading-gold' : 'hover:bg-logo-gold hover:text-black' }}">
                    {{ $brand->name }}
                </div>
            </a>
            @endforeach
        </div>

        <form action="{{ route('catalog') }}" method="GET" class="mb-8">
            <div class="flex shadow-lg">
                <input type="text" name="search" placeholder="Search by name" value="{!! old('search', request('search')) !!}"
                       class="w-full px-4 py-2 border border-logo-gold bg-black bg-opacity-50 rounded-l-lg text-menu-text focus:outline-none focus:ring-2 focus:ring-logo-gold transition-shadow duration-300">
                <button type="submit" class="bg-logo-gold text-button-text px-4 py-2 rounded-r-lg hover:bg-subheading-gold transition-colors duration-300">
                    Search
                </button>
            </div>
        </form>
        @php
        if(Auth::check()){
            $user = Auth::user();
            $wishlists = $user->wishlists;
        }
        @endphp
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($products as $product)
            <div class="relative group">
                <div class="bg-black bg-opacity-50 rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 group-hover:scale-105 flex flex-col h-full">
                    <div class="aspect-square flex items-center justify-center h-64"> 
                        <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" class="object-contain h-full w-auto">
                    </div>
                    <div class="p-4 flex-grow">
                        <h2 class="text-xl font-bold text-logo-gold">{{ $product->name }}</h2>
                        <p class="text-subheading-gold">${{ number_format($product->price, 2) }}</p>
                        <p class="text-subheading-gold">{{ $product->brand->name }}</p>
                    </div>
                    <div class="absolute inset-0 bg-black bg-opacity-75 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="text-center p-4">
                            <p class="text-white mb-4">{{ $product->description }}</p>
                            @if (Auth::check())
                                <button onclick="toggleWishlist('{{ $product->id }}')" class="bg-logo-gold text-button-text px-4 py-2 rounded hover:bg-subheading-gold transition-colors duration-300">
                                    @php
                                    if($wishlists->count() > 0 && $wishlists->contains($product)){
                                        echo "Remove from Wishlists";
                                    }
                                    else{
                                        echo "Add to Wishlists";
                                    }
                                    @endphp
                                </button>
                            @else
                                <button onclick="showLoginPrompt()" class="bg-logo-gold text-button-text px-4 py-2 rounded hover:bg-subheading-gold transition-colors duration-300">
                                    Add to Wishlist
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div id="loginModal" class="fixed inset-0 z-[60] hidden" style="background-color: rgba(0, 0, 0, 0.8); backdrop-filter: blur(5px);">
            <div class="min-h-screen flex items-center justify-center p-4">
                <div class="bg-black bg-opacity-50 rounded-lg p-8 max-w-md w-full mx-auto relative overflow-hidden border border-logo-gold/20">
                    <div class="absolute top-0 left-0 w-full h-[1px] bg-gradient-to-r from-transparent via-logo-gold to-transparent opacity-50"></div>
                    <div class="absolute bottom-0 left-0 w-full h-[1px] bg-gradient-to-r from-transparent via-logo-gold to-transparent opacity-50"></div>
                
                    <div class="relative text-center">
                        <div class="mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-logo-gold" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="1" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>

                        <h3 style="font-family: 'Playfair Display', serif;" class="text-3xl font-bold text-logo-gold mb-2">Authentication Required</h3>
                        
                        <div class="space-y-6 mb-8">
                            <p style="font-family: 'Playfair Display', serif;" class="text-heading-white text-3sm">
                                Please sign in to add items to your wishlist
                            </p>
                            
                            <div class="w-full h-px bg-gradient-to-r from-transparent via-logo-gold to-transparent opacity-30 my-6"></div>
                            
                            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center"> 
                                <button onclick="redirectToLogin()" 
                                        style="font-family: 'Playfair Display', serif;"
                                        class="w-full sm:w-auto px-8 py-3 bg-logo-gold text-button-text rounded-lg hover:bg-subheading-gold transition-all duration-300">
                                    Sign In
                                </button>
                                <button onclick="closeLoginModal()" 
                                        style="font-family: 'Playfair Display', serif;"
                                        class="w-full sm:w-auto px-8 py-3 border-2 border-logo-gold text-logo-gold rounded-lg hover:bg-logo-gold hover:text-black transition-all duration-300">
                                    Cancel
                                </button>
                            </div>
                        </div>
                        
                        <div class="pt-4 border-t border-logo-gold/20">
                            <p style="font-family: 'Playfair Display', serif;" class="text-heading-white text-2sm">
                                Don't have an account? 
                                <a href="{{ route('register') }}" 
                                class="text-logo-gold hover:text-subheading-gold transition-colors duration-300">
                                    Register here
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    window.showLoginPrompt = function() {
        const modal = document.getElementById('loginModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    };

    window.closeLoginModal = function() {
        const modal = document.getElementById('loginModal');
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    };

    window.redirectToLogin = function() {
        window.location.href = '{{ route('login') }}';
    };

    // Close modal when clicking outside
    const modal = document.getElementById('loginModal');
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeLoginModal();
        }
    });

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeLoginModal();
        }
    });
});

function toggleWishlist(productId) {
    fetch(`/wishlist/${productId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ 
            product_id: productId
        }),
        credentials: 'same-origin'
    })
    .then(response => {
        if(response.ok){
            window.location.reload()
        }
    })
    .then(data => {
        if (data.error) {
            alert(data.error);
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
</script>

<style>
@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.animate-fade-in {
    animation: fadeIn 0.3s ease-out;
}
</style>
@endsection