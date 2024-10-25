@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-navbar-bg py-12 px-4 sm:px-6 lg:px-8">
    <div class="w-full"> 
    <h1 class="text-5xl font-display font-bold text-logo-gold text-center mb-6">Products</h1>

    <form action="{{ route('admin.dashboard') }}" method="GET" class="mb-8">
        <div class="flex shadow-lg">
            <input type="text" name="search" placeholder="Search by name" value="{!! old('search', request('search')) !!}"
                    class="w-full px-4 py-2 border border-logo-gold bg-black bg-opacity-50 rounded-l-lg text-menu-text focus:outline-none focus:ring-2 focus:ring-logo-gold transition-shadow duration-300">
            <button type="submit" class="bg-logo-gold text-button-text px-4 py-2 rounded-r-lg hover:bg-subheading-gold transition-colors duration-300">
                Search
            </button>
        </div>
    </form>

        <div class="bg-black bg-opacity-50 rounded-lg shadow-lg p-6">
            <table class="w-full text-left divide-y divide-gray-700">
                <thead class="text-logo-gold uppercase">
                    <tr>
                        <th class="px-6 py-4">Image</th>
                        <th class="px-6 py-4">Name</th>
                        <th class="px-6 py-4">Brand</th>
                        <th class="px-6 py-4 text-right">Price</th>
                        <th class="px-6 py-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800 text-menu-text">
                    @foreach ($products as $product)
                        <tr class="hover:bg-gray-900 transition-colors duration-300">
                            <td class="px-6 py-4">
                                <img src="{{ asset($product->image_path) }}" alt="{{ $product->name }}" class="w-16 h-16 object-cover rounded-md shadow-lg">
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-lg">{{ $product->name }}</span>
                                <p class="text-sm text-gray-400">{{ Str::limit($product->description, 50) }}</p>
                            </td>
                            <td class="px-6 py-4">{{ $product->brand->name }}</td> 
                            <td class="px-6 py-4 text-right">${{ $product->price }}</td>
                            <td class="px-6 py-4 text-right space-x-4">
                                <div class="flex justify-start gap-4">
                                    <a href="{{ route('admin.products.edit', $product->id) }}" class="text-yellow-500 hover:text-yellow-300 transition-colors duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 20h9m-11-1l9-9a2.25 2.25 0 10-3.182-3.182l-9 9V19.5h3.75z" />
                                        </svg>
                                    </a>

                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-400 transition-colors duration-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-1 12a2 2 0 01-2 2H8a2 2 0 01-2-2L5 7m5-4h4m-4 0a1 1 0 011-1h2a1 1 0 011 1m-4 0h4m7 0H5" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-6">
                {{ $products->appends(request()->input())->links('components.pagination') }}
            </div>
        </div>
    </div>
</div>
@endsection
