@php
$navItems = [
    ['name' => 'Home', 'href' => '/'],
    ['name' => 'Catalog', 'href' => '/catalog'],
    ['name' => 'About', 'href' => '/about'],
    ['name' => 'Contact', 'href' => '/contact'],
];
@endphp

<nav class="bg-white shadow-lg">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex justify-between">
            <div class="flex space-x-7">
                <div>
                    <a href="/" class="flex items-center py-4 px-2">
                        <span class="font-semibold text-gray-500 text-lg">ChronoChic</span>
                    </a>
                </div>
            </div>
            <div class="hidden md:flex items-center space-x-3">
                @foreach($navItems as $item)
                    <a href="{{ $item['href'] }}" class="py-4 px-2 text-gray-500 font-semibold hover:text-green-500 transition duration-300">
                        {{ $item['name'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</nav>
