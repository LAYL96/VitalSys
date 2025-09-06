<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-xl font-bold text-gray-800 dark:text-gray-200">VitalSys</a>

            <!-- Buscador -->
            <form action="{{ route('products.public') }}" method="GET" class="flex">
                <input type="text" name="search" placeholder="Buscar productos..." value="{{ request('search') }}"
                    class="px-4 py-2 rounded-l border border-gray-300 focus:outline-none focus:ring focus:border-blue-500">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-r hover:bg-blue-700 transition">Buscar</button>
            </form>

            <!-- Botones Login/Register -->
            @guest
                <div class="space-x-2">
                    <a href="{{ route('login') }}"
                        class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-900 transition">Login</a>
                    <a href="{{ route('register') }}"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Registrarse</a>
                </div>
            @endguest
        </div>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <h2 class="text-2xl font-semibold mb-6">Productos Disponibles</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($products as $product)
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                            class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">
                            Sin Imagen
                        </div>
                    @endif
                    <div class="p-4">
                        <h3 class="font-semibold text-lg">{{ $product->name }}</h3>
                        <p class="text-gray-600 dark:text-gray-300 text-sm mb-2">
                            {{ Str::limit($product->description, 80) }}
                        </p>
                        <p class="font-bold text-blue-600">${{ $product->price }}</p>
                        <a href="{{ route('products.show', $product->id) }}"
                            class="mt-2 inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Ver Detalle
                        </a>
                    </div>
                </div>
            @empty
                <p>No se encontraron productos.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
