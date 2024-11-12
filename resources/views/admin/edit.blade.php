@extends('layouts.app')
@section('title', 'Edit')
@section('content')
<div class="min-h-screen bg-navbar-bg py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
    <div class="mb-8 flex">
        <a href="{{ route('admin.dashboard') }}" 
            class="text-logo-gold hover:bg-logo-gold hover:text-black font-bold py-2 px-4 rounded-full transition-all duration-300 inline-flex items-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M15 19l-7-7 7-7"/>
            </svg>
        </a>
        <h1 class="text-5xl w-full text-center font-display font-bold text-logo-gold">Edit Product</h1>
    </div>


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

        <div class="bg-black bg-opacity-50 rounded-lg shadow-lg p-6">
            <form action="{{ route('admin.products.update', $product) }}" 
                  method="POST" 
                  enctype="multipart/form-data"
                  class="space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="name" class="block text-sm font-medium text-logo-gold">
                        Product Name <span class="text-error-text">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name"
                           value="{{ old('name', $product->name) }}"
                           class="{{'mt-1 px-3 w-full rounded-lg bg-black bg-opacity-50 text-menu-text transition-shadow duration-300 ' . 
                            ($errors->has('name') ? 'border-error-text focus:ring-2 focus:ring-error-text' : 'border-2 border-logo-gold focus:ring-2 focus:ring-logo-gold') }}"
                           required>
                    @error('name')
                        <p class="mt-1 text-sm text-error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-logo-gold">
                        Description <span class="text-error-text">*</span>
                    </label>
                    <textarea name="description" 
                             id="description"
                             rows="4"
                             class="mt-1 px-3 block w-full rounded-lg bg-black bg-opacity-50 text-menu-text transition-shadow duration-300
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

                <div>
                    <label for="price" class="block text-sm font-medium text-logo-gold">
                        Price (Rp)<span class="text-error-text">*</span>
                    </label>
                    <div class="mt-1 relative rounded-lg shadow-sm">
                        <input type="number" 
                               name="price" 
                               id="price"
                               value="{{ old('price', $product->price) }}"
                               min="0"
                               step="1000"
                               class="pl-12 px-3 block w-full rounded-lg bg-black bg-opacity-50 text-menu-text transition-shadow duration-300
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

                <div>
                    <label for="brand_id" class="block text-sm font-medium text-logo-gold">
                        Brand <span class="text-error-text">*</span>
                    </label>
                    <select name="brand_id" 
                            id="brand_id"
                            class="mt-1 px-2 block w-full rounded-lg bg-black bg-opacity-50 text-menu-text transition-shadow duration-300
                            @error('brand_id') 
                                border-2 border-error-text focus:ring-2 focus:ring-error-text
                            @else 
                                border-2 border-logo-gold focus:ring-2 focus:ring-logo-gold
                            @enderror"
                            required>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ $product->brand_id == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('brand_id')
                        <p class="mt-1 text-sm text-error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-logo-gold">
                        Product Image
                    </label>
                    
                    @if($product->image_path)
                    <div class="mt-2 flex items-center space-x-4">
                        <img id="preview" src="{{ asset($product->image_path) }}" 
                             alt="{{ $product->name }}"
                             class="h-28 object-cover rounded-lg border-2 border-logo-gold">
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
                    <p class="mt-1 text-xs text-subheading-gold">
                        Leave empty to keep current image. Accepted formats: JPG, JPEG, PNG. Maximum size: 2MB
                    </p>

                    <div id="imagePreviewContainer" class="mt-4 hidden">
                        <h4 class="text-subheading-gold mb-2">Preview:</h4>
                        <img id="imagePreview" class="h-32 w-32 object-cover rounded-lg border-2 border-logo-gold">
                    </div>
                </div>

                <div class="flex justify-end space-x-3 pt-6">
                    <button type="submit" 
                            class="bg-logo-gold text-text-brown hover:bg-subheading-gold font-bold py-2 px-4 rounded-full transition-all duration-300 inline-flex items-center">
                        Save Changes
                    </button>
                </div>
            </form>
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

        const reader = new FileReader();
        reader.onload = function(event) {
            document.getElementById('imagePreview').src = event.target.result;
            document.getElementById('imagePreviewContainer').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
