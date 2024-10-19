@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-navbar-bg py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-5xl font-display font-bold text-logo-gold text-center mb-12">Exquisite Timepieces</h1>
        
        <div class="mb-8 flex justify-center gap-4">
            <a href="{{ route('catalog') }}" class="w-dvw sm:w-1/4 lg:w-1/6 px-2 py-4 border-2 border-subheading-gold bg-subheading-gold text-white rounded-full text-center cursor-pointer transition-all duration-300 {{ request('brand') == '' ? 'bg-logo-gold text-black border-subheading-gold' : 'hover:bg-logo-gold hover:text-black' }}">
                All Brands
            </a>
            @foreach($brands as $brand)
                <a href="{{ route('catalog', ['brand' => $brand->id]) }}" class="w-full sm:w-1/4 lg:w-1/6 px-4 py-4 border-2 border-logo-gold text-white bg-transparent rounded-full text-center cursor-pointer transition-all duration-300 {{ request('brand') == $brand->id ? 'bg-logo-gold text-black border-subheading-gold' : 'hover:bg-logo-gold hover:text-black' }}">
                    {{ $brand->name }}
                </a>
            @endforeach
        </div>

        <form action="{{ route('catalog') }}" method="GET" class="flex h-[500">
            <div class="flex shadow-lg">
                <input type="text" name="search" placeholder="Search by name" value="{{ request('search') }}"
                       class="w-dvw sm:w-64 px-4 py-2 border border-logo-gold bg-black bg-opacity-50 rounded-l-lg text-menu-text focus:outline-none focus:ring-2 focus:ring-logo-gold transition-shadow duration-300">
                <button type="submit" class="bg-logo-gold text-button-text px-4 py-2 rounded-r-lg hover:bg-subheading-gold transition-colors duration-300">
                    Search
                </button>
            </div>
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($products as $product)
            <div class="relative group">
                <div class="bg-black bg-opacity-50 rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 group-hover:scale-105">
                    <img src="{{ asset('storage/' . $product->image_path) }}" alt="{{ $product->name }}" class="w-full h-64 object-cover">
                    <div class="p-4">
                        <h2 class="text-xl font-bold text-logo-gold">{{ $product->name }}</h2>
                        <p class="text-subheading-gold">${{ number_format($product->price, 2) }}</p>
                        <p class="text-subheading-gold">{{ $product->brand->name }}</p>
                    </div>
                    <div class="absolute inset-0 bg-black bg-opacity-75 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="text-center p-4">
                            <p class="text-white mb-4">{{ $product->description }}</p>
                            @auth
                            <button onclick="toggleWishlist('{{ $product->id }}')" class="bg-logo-gold text-button-text px-4 py-2 rounded hover:bg-subheading-gold transition-colors duration-300">
                                Add to Wishlist
                            </button>
                            @else
                            <button onclick="showLoginPrompt()" class="bg-logo-gold text-button-text px-4 py-2 rounded hover:bg-subheading-gold transition-colors duration-300">
                                Add to Wishlist
                            </button>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-12 text-center text-subheading-gold">
            <p>For your security and convenience, these exquisite timepieces are available for purchase at our offline stores.</p>
            <p>Visit us to experience the luxury firsthand.</p>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleWishlist(productId) {
    fetch(`/wishlist/${productId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
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

function showLoginPrompt() {
    if(confirm('You need to log in to add items to your wishlist. Would you like to log in now?')) {
        window.location.href = '{{ route('login') }}';
    }
}
</script>
@endpush
@endsection
