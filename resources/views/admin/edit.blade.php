{{-- resources/views/admin/products/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-navbar-bg py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        {{-- Header --}}
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <h1 class="text-4xl font-display font-bold text-logo-gold">Edit Product</h1>
                <a href="{{ route('admin.dashboard') }}" 
                   class="border-2 border-logo-gold text-logo-gold hover:bg-logo-gold hover:text-black font-bold py-2 px-4 rounded-full transition-all duration-300 inline-flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Back to Dashboard
                </a>
            </div>
            <p class="mt-2 text-sm text-subheading-gold">Update the product details below</p>
        </div>

        {{-- Alert Messages --}}
        @if (session('success'))
        <div class="border-l-4 border-logo-gold bg-black bg-opacity-50 text-logo-gold p-4 mb-6">
            <p class="font-bold">Success</p>
            <p>{{ session('success') }}</p>
        </div>
        @endif

        @if (session('error'))
        <div class="border-l-4 border-error-text bg-black bg-opacity-50 text-error-text p-4 mb-6">
            <p class="font-bold">Error</p>
            <p>{{ session('error') }}</p>
        </div>
        @endif

        @if ($errors->any())
        <div class="border-l-4 border-error-text bg-black bg-opacity-50 text-error-text p-4 mb-6">
            <p class="font-bold">Please correct the following errors:</p>
            <ul class="mt-2 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        {{-- Edit Form --}}
        <div class="bg-black bg-opacity-50 rounded-lg shadow-lg p-6">
            <form action="{{ route('admin.products.update', $product) }}" 
                  method="POST" 
                  enctype="multipart/form-data"
                  class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Name Field --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-logo-gold">
                        Product Name <span class="text-error-text">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name"
                           value="{{ old('name', $product->name) }}"
                           class="mt-1 block w-full rounded-lg bg-black bg-opacity-50 text-menu-text transition-shadow duration-300
                           @error('name') 
                               border-error-text focus:ring-2 focus:ring-error-text 
                           @else 
                               border-2 border-logo-gold focus:ring-2 focus:ring-logo-gold
                           @enderror"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-error-text">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description Field --}}
                <div>
                    <label for="description" class="block text-sm font-medium text-logo-gold">
                        Description <span class="text-error-text">*</span>
                    </label>
                    <textarea name="description" 
                             id="description"
                             rows="4"
                             class="mt-1 block w-full rounded-lg bg-black bg-opacity-50 text-menu-text transition-shadow duration-300
                             @error('description') 
                                 border-2 border-error-text focus:ring-2 focus:ring-error-text
                             @else 
                                 border-2 border-logo-gold focus:ring-2 focus:ring-logo-gold
                             @enderror"
                             required>{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-error-text">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Price Field --}}
                <div>
                    <label for="price" class="block text-sm font-medium text-logo-gold">
                        Price (Rp) <span class="text-error-text">*</span>
                    </label>
                    <div class="mt-1 relative rounded-lg shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-subheading-gold sm:text-sm">Rp</span>
                        </div>
                        <input type="number" 
                               name="price" 
                               id="price"
                               value="{{ old('price', $product->price) }}"
                               min="0"
                               step="1000"
                               class="pl-12 block w-full rounded-lg bg-black bg-opacity-50 text-menu-text transition-shadow duration-300
                               @error('price') 
                                   border-2 border-error-text focus:ring-2 focus:ring-error-text
                               @else 
                                   border-2 border-logo-gold focus:ring-2 focus:ring-logo-gold
                               @enderror"
                               required>
                    </div>
                    @error('price')
                        <p class="mt-1 text-sm text-error-text">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Brand Field --}}
                <div>
                    <label for="brand" class="block text-sm font-medium text-logo-gold">
                        Brand <span class="text-error-text">*</span>
                    </label>
                    <input type="text" 
                           name="brand" 
                           id="brand"
                           value="{{ old('brand', $product->brand) }}"
                           class="mt-1 block w-full rounded-lg bg-black bg-opacity-50 text-menu-text transition-shadow duration-300
                           @error('brand') 
                               border-2 border-error-text focus:ring-2 focus:ring-error-text
                           @else 
                               border-2 border-logo-gold focus:ring-2 focus:ring-logo-gold
                           @enderror"
                           required>
                    @error('brand')
                        <p class="mt-1 text-sm text-error-text">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Image Field --}}
                <div>
                    <label for="image" class="block text-sm font-medium text-logo-gold">
                        Product Image
                    </label>
                    
                    {{-- Current Image Preview --}}
                    @if($product->image_path)
                    <div class="mt-2 flex items-center space-x-4">
                        <img src="{{ Storage::url($product->image_path) }}" 
                             alt="{{ $product->name }}"
                             class="h-24 w-24 object-cover rounded-lg border-2 border-logo-gold">
                        <p class="text-sm text-subheading-gold">Current image</p>
                    </div>
                    @endif

                    <div class="mt-2">
                        <input type="file" 
                               name="image" 
                               id="image"
                               accept="image/jpeg,image/png,image/jpg"
                               class="block w-full text-subheading-gold transition-all duration-300
                               @error('image')
                                   file:border-2 file:border-error-text file:text-error-text hover:file:bg-error-text
                               @else
                                   file:border-2 file:border-logo-gold file:text-logo-gold hover:file:bg-logo-gold
                               @enderror
                               file:mr-4 file:py-2 file:px-4 file:rounded-full file:bg-transparent hover:file:text-black file:transition-all file:duration-300">
                    </div>
                    @error('image')
                        <p class="mt-1 text-sm text-error-text">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-subheading-gold">
                        Leave empty to keep current image. Accepted formats: JPG, JPEG, PNG. Maximum size: 2MB
                    </p>
                </div>

                {{-- Action Buttons --}}
                <div class="flex justify-end space-x-3 pt-6">
                    <button type="button" 
                            onclick="window.location.href='{{ route('admin.dashboard') }}'"
                            class="border-2 border-logo-gold text-logo-gold hover:bg-logo-gold hover:text-black font-bold py-2 px-4 rounded-full transition-all duration-300 inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancel
                    </button>
                    <button type="submit" 
                            class="bg-logo-gold text-black hover:bg-subheading-gold font-bold py-2 px-4 rounded-full transition-all duration-300 inline-flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Save Changes
                    </button>
                </div>
            </form>

            {{-- Delete Product Section --}}
            <div class="mt-10 pt-6 border-t border-logo-gold">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-medium text-error-text">Danger Zone</h2>
                    <form action="{{ route('admin.products.destroy', $product) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-error-text hover:bg-error-text/80 text-black font-bold py-2 px-4 rounded-full transition-all duration-300 inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete Product
                        </button>
                    </form>
                </div>
                <p class="mt-2 text-sm text-subheading-gold">
                    Once you delete a product, there is no going back. Please be certain.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        if (!file.type.match('image.*')) {
            alert('Please select an image file');
            this.value = '';
            return;
        }
        
        if (file.size > 2 * 1024 * 1024) {
            alert('File size should not exceed 2MB');
            this.value = '';
            return;
        }
    }
});
</script>
@endsection