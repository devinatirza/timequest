@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-navbar-bg py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex flex-row md:flex-row gap-8">
            <!-- Left Side - Profile -->
            <div class="w-1/4">
                <div class="bg-black bg-opacity-50 rounded-lg shadow-lg p-6 border border-logo-gold/30">
                    <div class="flex justify-center mb-6">
                        <img id="preview" 
                             src="{{ $user->image_path ? route('user.image', ['userId' => $user->id, 'filename' => $user->image_path ? basename($user->image_path) : null, 'v' => time()]) : asset('images/default-profile.jpg') }}"
                             alt="{{ $user->name }}'s profile picture"
                             class="w-32 h-32 rounded-full border-2 border-logo-gold">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-subheading-gold">Name</label>
                        <p class="mt-1 text-sm text-menu-text">{{ $user->name }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-subheading-gold">Email</label>
                        <p class="mt-1 text-sm text-menu-text">{{ $user->email }}</p>
                    </div>
                    <div class="mt-6">
                        <a href="{{ route('profile.edit') }}" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-button-text bg-logo-gold hover:bg-subheading-gold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-logo-gold">
                            Edit Profile
                        </a>
                    </div>
                    <div class="mt-4">
                        <a href="{{ route('logout') }}"
                           class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Logout
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Side - Wishlist -->
            <div class="w-3/4">
                <h2 class="text-2xl font-bold text-logo-gold mb-6">Your Wishlist</h2>
                @if($user->wishlists->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($user->wishlists as $product)
                        <div class="relative group">
                            <div class="bg-black bg-opacity-50 rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 group-hover:scale-105 flex flex-col h-full">
                                <div class="aspect-square flex items-center justify-center h-64"> 
                                    <img src="{{ asset($product->image_path) }}" 
                                         alt="{{ $product->name }}" 
                                         class="object-contain h-full w-auto">
                                </div>
                                <div class="p-4 flex flex-col h-full justify-between">
                                    <div>
                                        <h4 class="text-lg font-bold text-logo-gold truncate">{{ $product->name }}</h4>
                                        <p class="text-subheading-gold">${{ number_format($product->price, 2) }}</p>
                                        <p class="text-subheading-gold mb-3">{{ $product->brand->name }}</p>
                                    </div>
                                    <button onclick="removeFromWishlist('{{ $product->id }}')" 
                                            class="w-full py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none">
                                        Remove from Wishlist
                                    </button>
                                </div>

                                <!-- Button at the bottom of the card
                                <div class="p-4 mt-auto flex h-full items-end">
                                    <div class="w-full h-full">
                                        
                                    </div>
                                </div> -->
                            </div>
                        </div>
                            
                        @endforeach
                    </div>
                @else
                    <div class="bg-black bg-opacity-50 rounded-lg p-8 text-center border border-logo-gold/30">
                        <p class="text-subheading-gold text-lg mb-4">Your wishlist is empty</p>
                        <a href="{{ route('catalog') }}" 
                           class="inline-block px-6 py-2 bg-logo-gold text-button-text rounded-md hover:bg-subheading-gold transition-colors duration-300">
                            Browse Catalog
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function removeFromWishlist(productId) {
    if(!confirm('Are you sure you want to remove this item from your wishlist?')) return;
    
    fetch(`/wishlist/${productId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if(response.ok){
            window.location.reload()
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>
@endsection