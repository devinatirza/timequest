@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-12 pb-96">
    <h1 class="text-5xl font-display font-bold text-logo-gold text-center mb-12">Exquisite Timepieces</h1>
    
    <!-- Search and Brand Filter -->
    <div class="mb-8 flex justify-center">
        <a href="{{ route('catalog') }}" class="w-auto sm:w-1/4 lg:w-1/6 px-2 flex">
            <div class="w-full py-4 text-center cursor-pointer transition-all duration-300 flex items-center justify-center rounded-full
                {{ request('brand') == '' ? 'bg-logo-gold text-white' : 'bg-subheading-gold text-text-brown hover:bg-logo-gold hover:text-text-brown' }}">
                All Products
            </div>
        </a>
        @foreach($brands as $brand)
        <a href="{{ route('catalog', ['brand' => $brand->id]) }}" class="w-auto sm:w-1/4 lg:w-1/6 px-2 flex">
            <div class="w-full py-4 border-2 border-logo-gold text-center cursor-pointer transition-all duration-300 flex items-center justify-center rounded-full
                {{ request('brand') == $brand->id ? 'bg-logo-gold text-white' : 'bg-subheading-gold text-text-brown hover:bg-logo-gold hover:text-text-brown' }}">
                {{ $brand->name }}
            </div>
        </a>
        @endforeach
    </div>

    <div class="mb-8">
        <div class="flex shadow-lg">
            <input type="text" id="searchInput" name="search" placeholder="Search by name" 
                class="w-full px-4 py-2 border border-logo-gold bg-navbar-bg bg-opacity-50 rounded-l-lg text-menu-text focus:outline-none focus:ring-2 focus:ring-logo-gold transition-shadow duration-300">
            <button id="searchButton" type="button" class="bg-logo-gold text-button-text px-4 py-2 rounded-r-lg hover:bg-subheading-gold transition-colors duration-300">
                Search
            </button>
        </div>
    </div>


    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8" id="productContainer">
        @foreach($products as $product)
        <div class="relative group">
            <div class="bg-black bg-opacity-50 rounded-lg shadow-lg overflow-hidden transform transition-transform duration-300 group-hover:scale-105 flex flex-col h-full">
                <!-- Product Image and Details -->
                <div class="aspect-square flex items-center justify-center h-64"> 
                    <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" class="object-contain h-full w-auto">
                </div>
                <div class="p-4 flex-grow">
                    <h2 class="text-xl font-bold text-logo-gold">{{ $product->name }}</h2>
                    <p class="text-subheading-gold">${{ number_format($product->price, 2) }}</p>
                    <p class="text-subheading-gold">{{ $product->brand->name }}</p>
                </div>

                <!-- Hover Overlay with Wishlist and Compare Button -->
                <div class="absolute inset-0 bg-black bg-opacity-75 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <div class="text-center p-4">
                        <p class="text-white mb-4">{{ $product->description }}</p>

                        <!-- Wishlist Button -->
                        @if (Auth::check())
                            <button onclick="toggleWishlist('{{ $product->id }}')" class="bg-logo-gold text-button-text px-4 py-2 rounded hover:bg-subheading-gold transition-colors duration-300">
                                @php
                                if(Auth::user()->wishlists->contains($product)){
                                    echo "Remove from Wishlist";
                                } else {
                                    echo "Add to Wishlist";
                                }
                                @endphp
                            </button>
                        @else
                            <button onclick="showLoginPrompt()" class="bg-logo-gold text-button-text px-4 py-2 rounded hover:bg-subheading-gold transition-colors duration-300">
                                Add to Wishlist
                            </button>
                        @endif

                        <!-- Compare Button -->
                        <button onclick="toggleComparison('{{ $product->id }}', '{{ $product->name }}', '{{ asset($product->image_path) }}')" 
                                class="mt-4 px-4 py-2 rounded transition-colors duration-300 bg-button-gray text-gray-800 hover:bg-button-light-gray">
                            Compare
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Comparison Bar at the Bottom -->
    <div id="comparisonBar" style="display: none; position: fixed; bottom: 0; left: 0; right: 0; background-color: #333; color: white; padding: 16px; display: flex; align-items: center; justify-content: space-between; border-top: 2px solid #D4AF37; box-shadow: 0 -4px 6px rgba(0, 0, 0, 0.1); z-index: 50;">
        <div class="flex items-center gap-4 overflow-x-auto">
            <template id="compareItemTemplate">
                <div class="flex items-center bg-navbar-bg bg-opacity-50 px-4 py-2 rounded-full space-x-2 relative">
                    <!-- Center-Cropped Image -->
                    <img src="" alt="Product" class="w-12 h-12 object-cover object-center rounded-full">
                    
                    <!-- Product Name -->
                    <span class="text-logo-gold"></span>
                    
                    <!-- Remove Button (Red "×") -->
                    <button onclick="removeCompareItem(this)" class="text-button-red hover:text-button-red-hover font-bold ml-2 text-xl transition-colors duration-300">
                        &times;
                    </button>
                </div>
            </template>
        </div>

        <!-- Compare Button -->
        <div class="flex items-center gap-4">
            <button onclick="showComparison()" id="compareButton" class="bg-logo-gold text-black px-4 py-2 rounded transition-colors duration-300" style="display: none;">
                Compare (2)
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const searchButton = document.getElementById('searchButton');
    let typingTimer;

    searchInput.addEventListener('input', function() {
        clearTimeout(typingTimer); 
        typingTimer = setTimeout(fetchUpdates, 500); 
    });

    searchButton.addEventListener('click', fetchUpdates);

    function fetchUpdates() {
        const brandId = "{{ request('brand') }}";
        const searchQuery = searchInput.value; 

        fetch(`/catalog/fetch-updates?brand=${brandId}&search=${encodeURIComponent(searchQuery)}`)
            .then(response => response.json())
            .then(data => {
                const productContainer = document.getElementById('productContainer');
                productContainer.innerHTML = ''; 
                
                data.forEach(product => {
                    const productElement = `
                        <div class="relative group bg-black bg-opacity-50 rounded-lg shadow-lg p-4">
                            <div class="aspect-square h-64 flex items-center justify-center"> 
                                <img src="${product.image_path}" alt="${product.name}" class="object-contain h-full w-auto">
                            </div>
                            <h2 class="text-xl font-bold text-logo-gold mt-4">${product.name}</h2>
                            <p class="text-subheading-gold">${product.price}</p>
                            <p class="text-subheading-gold">${product.brand.name}</p>
                        </div>
                    `;
                    productContainer.insertAdjacentHTML('beforeend', productElement);
                });
            })
            .catch(error => console.error('Error fetching updates:', error));
    }
});

let selectedProducts = [];

function toggleComparison(id, name, imgSrc) {
    const index = selectedProducts.findIndex(item => item.id === id);
    
    if (index === -1) { 
        if (selectedProducts.length >= 2) {
            alert("You can only compare up to 2 products.");
            return;
        }
        selectedProducts.push({ id, name, imgSrc });
        addCompareItem(id, imgSrc, name);
    } else { 
        selectedProducts.splice(index, 1);
        removeCompareItemById(id);
    }

    updateComparisonBar();
}

function addCompareItem(id, imgSrc, name) {
    const template = document.getElementById('compareItemTemplate').content.cloneNode(true);
    template.querySelector('img').src = imgSrc;
    template.querySelector('span').textContent = name;
    template.querySelector('button').setAttribute('data-id', id);
    document.getElementById('comparisonBar').querySelector('.flex.items-center.gap-4').appendChild(template);
}

function removeCompareItem(button) {
    const id = button.getAttribute('data-id');
    selectedProducts = selectedProducts.filter(item => item.id !== id);
    button.closest('.flex').remove();
    document.querySelectorAll('.compare-button').forEach(btn => {
        if (btn.getAttribute('onclick').includes(id)) btn.innerText = "Compare";
    });

    updateComparisonBar();
}

function showComparison() {
    if (selectedProducts.length === 2) {
        const ids = selectedProducts.map(item => item.id).join(',');
        window.location.href = `/compare-products?products=${ids}`;
    }
}

function updateComparisonBar() {
    const comparisonBar = document.getElementById('comparisonBar');
    const compareButton = document.getElementById('compareButton');

    comparisonBar.style.display = selectedProducts.length > 0 ? 'flex' : 'none';
    compareButton.style.display = selectedProducts.length === 2 ? 'inline-block' : 'none';
}
</script>
@endsection