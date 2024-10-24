{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-navbar-bg py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        {{-- Header Section --}}
        <div class="mb-8 flex justify-between items-center">
            <h1 class="text-4xl font-display font-bold text-logo-gold">Products Dashboard</h1>
            <a href="{{ route('admin.products.create') }}" 
               class="bg-logo-gold text-black hover:bg-subheading-gold font-bold py-2 px-6 rounded-full transition-all duration-300 inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add New Product
            </a>
        </div>

        {{-- Alert Messages --}}
        @if (session('success'))
        <div class="border-l-4 border-logo-gold bg-black bg-opacity-50 text-logo-gold p-4 mb-6">
            <p>{{ session('success') }}</p>
        </div>
        @endif

        @if (session('error'))
        <div class="border-l-4 border-error-text bg-black bg-opacity-50 text-error-text p-4 mb-6">
            <p>{{ session('error') }}</p>
        </div>
        @endif

        {{-- Search Section --}}
        <div class="mb-6">
            <form action="{{ route('admin.dashboard') }}" method="GET" class="flex shadow-lg">
                <input type="text" 
                       name="search" 
                       placeholder="Search products..." 
                       value="{{ request('search') }}"
                       class="w-full px-4 py-2 border-2 border-logo-gold bg-black bg-opacity-50 rounded-l-full text-menu-text focus:outline-none focus:ring-2 focus:ring-logo-gold transition-shadow duration-300">
                <button type="submit" 
                        class="bg-logo-gold text-black px-6 py-2 rounded-r-full hover:bg-subheading-gold transition-colors duration-300">
                    Search
                </button>
            </form>
        </div>

        {{-- Products Table --}}
        <div class="bg-black bg-opacity-50 rounded-lg shadow-xl overflow-hidden">
            <table class="min-w-full divide-y divide-logo-gold">
                <thead>
                    <tr class="bg-black bg-opacity-50">
                        <th class="px-6 py-4 text-left text-xs font-medium text-logo-gold uppercase tracking-wider">Image</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-logo-gold uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-logo-gold uppercase tracking-wider">Brand</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-logo-gold uppercase tracking-wider">Price</th>
                        <th class="px-6 py-4 text-left text-xs font-medium text-logo-gold uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-logo-gold">
                    @forelse ($products as $product)
                    <tr class="hover:bg-black hover:bg-opacity-70 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <img src="{{ Storage::url($product->image_path) }}" 
                                 alt="{{ $product->name }}" 
                                 class="h-16 w-16 object-cover rounded-lg border-2 border-logo-gold">
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-menu-text font-medium">{{ $product->name }}</div>
                            <div class="text-subheading-gold text-sm mt-1 truncate max-w-xs">
                                {{ Str::limit($product->description, 50) }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-subheading-gold">{{ $product->brand }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-logo-gold">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-4">
                                <a href="{{ route('admin.products.edit', $product) }}" 
                                   class="text-logo-gold hover:text-subheading-gold transition-colors duration-300 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" 
                                      method="POST" 
                                      class="inline-block"
                                      onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="text-error-text hover:text-error-text/80 transition-colors duration-300 flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-subheading-gold">
                            No products found
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection