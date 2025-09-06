<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-xl font-bold text-gray-800 dark:text-gray-200">VitalSys</a>
            <a href="{{ route('products.public') }}"
                class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-900 transition">Volver a productos</a>
        </div>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg overflow-hidden">
            @if ($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                    class="w-full h-64 object-cover">
            @endif
            <div class="p-6">
                <h2 class="text-2xl font-bold mb-4">{{ $product->name }}</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-4">{{ $product->description }}</p>
                <p class="font-bold text-blue-600 text-xl mb-2">Precio: ${{ $product->price }}</p>
                <p class="text-gray-600 dark:text-gray-400 mb-2">Stock disponible: {{ $product->stock }}</p>
                <p class="text-gray-600 dark:text-gray-400 mb-2">Categoría:
                    {{ $product->category?->name ?? 'Sin categoría' }}</p>
                <p class="text-gray-600 dark:text-gray-400 mb-2">Proveedor:
                    {{ $product->supplier?->name ?? 'Sin proveedor' }}</p>
                @if ($product->expiration_date)
                    <p class="text-red-600 mb-2">Fecha de caducidad: {{ $product->expiration_date }}</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
