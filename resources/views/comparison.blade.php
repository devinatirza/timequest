@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-12">
    <h1 class="text-5xl text-center font-display font-bold text-logo-gold mb-12">Product Comparison</h1>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
        @foreach($products as $product)
            <div class="bg-black bg-opacity-50 rounded-lg p-6 shadow-lg flex flex-col">
                
                <!-- Product Image and Name Section -->
                <div class="flex flex-col items-center mb-6">
                    <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" class="w-48 h-48 object-cover rounded mb-4">
                    <h3 class="text-2xl font-bold text-logo-gold">{{ $product->name }}</h3>
                </div>

                <div class="flex-grow">
                    <hr class="border-t border-logo-gold mb-4">
                    
                    <!-- Specifications Section -->
                    <div class="text-white text-sm space-y-4">
                        <h4 class="text-2xl font-bold text-logo-gold">Specifications</h4>
                    
                        <p><span class="text-subheading-gold font-bold">Brand:</span> 
                           <span class="text-gray-300">{{ $product->brand->name }}</span>
                        </p>
                        
                        <p><span class="text-subheading-gold font-bold">Price:</span> 
                           <span class="text-gray-300">${{ number_format($product->price, 2) }}</span>
                        </p>
                        
                        <p><span class="text-subheading-gold font-bold">Description:</span> 
                           <span class="text-gray-300">{{ $product->description }}</span>
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Close Comparison Button at the Bottom -->
    <div class="flex justify-center">
        <a href="{{ route('catalog') }}" 
            class="bg-logo-gold text-black font-bold py-3 px-6 rounded-full transition-all duration-300 hover:bg-subheading-gold hover:text-black">
            Close Comparison
        </a>
    </div>
</div>
@endsection
