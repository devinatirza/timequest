{{-- resources/views/admin/products/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-navbar-bg py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-3xl mx-auto">
        <div class="mb-8">
            <h1 class="text-5xl text-center font-display font-bold text-logo-gold">Add New Product</h1>
        </div>

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
            <form action="{{ route('admin.products.store') }}" 
                  method="POST" 
                  enctype="multipart/form-data"
                  class="space-y-6">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-logo-gold">
                        Product Name <span class="text-error-text">*</span>
                    </label>
                    <input type="text" 
                           name="name" 
                           id="name"
                           value="{{ old('name') }}"
                           class="mt-1 px-3 block w-full rounded-lg bg-black bg-opacity-50 text-menu-text transition-shadow duration-300
                           @error('name') 
                               border-2 border-error-text focus:ring-2 focus:ring-error-text 
                           @else 
                               border-2 border-logo-gold focus:ring-2 focus:ring-logo-gold
                           @enderror"
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
                             required>{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="price" class="block text-sm font-medium text-logo-gold">
                        Price ($) <span class="text-error-text">*</span>
                    </label>
                    <div class="mt-1 relative rounded-lg shadow-sm">
                        <input type="number" 
                               name="price" 
                               id="price"
                               value="{{ old('price') }}"
                               min="0"
                               step="1000"
                               class="pl-12 px-3block w-full rounded-lg bg-black bg-opacity-50 text-menu-text transition-shadow duration-300
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
                    <label for="brand" class="block text-sm font-medium text-logo-gold">
                        Brand <span class="text-error-text">*</span>
                    </label>
                    <select name="brand" 
                            id="brand"
                            class="mt-1 px-2 block w-full rounded-lg bg-black bg-opacity-50 text-menu-text transition-shadow duration-300
                            @error('brand') 
                                border-2 border-error-text focus:ring-2 focus:ring-error-text
                            @else 
                                border-2 border-logo-gold focus:ring-2 focus:ring-logo-gold
                            @enderror"
                            required>
                        <option value="">-- Select Brand --</option>
                        @foreach($brands as $brand)
                            <option value="{{ $brand->id }}" {{ old('brand') == $brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('brand')
                        <p class="mt-1 text-sm text-error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-logo-gold">
                        Product Image <span class="text-error-text">*</span>
                    </label>
                    <div class="mt-1">
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
                               file:mr-4 file:py-2 file:px-4 file:rounded-full file:bg-transparent hover:file:text-black file:transition-all file:duration-300"
                               required>
                    </div>

                    <div id="imagePreviewContainer" class="mt-4 hidden">
                        <img id="imagePreview" src="" alt="Image Preview" class="h-24 w-24 object-cover rounded-lg border-2 border-logo-gold">
                    </div>

                    @error('image')
                        <p class="mt-1 text-sm text-error-text">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-subheading-gold">
                        Accepted formats: JPG, JPEG, PNG. Maximum size: 2MB
                    </p>
                </div>

                <div class="flex justify-end space-x-3 pt-6">
                    <button type="submit" 
                            class="bg-logo-gold text-text-brown hover:bg-subheading-gold font-bold py-2 px-4 rounded-full transition-all duration-300 inline-flex items-center">
                        Create Product
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
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('imagePreviewContainer').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
