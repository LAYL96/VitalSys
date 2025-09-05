<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalle del Producto') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    <div>
                        <strong>Nombre:</strong> {{ $product->name }}
                    </div>

                    <div>
                        <strong>SKU:</strong> {{ $product->sku }}
                    </div>

                    <div>
                        <strong>Categoría:</strong> {{ $product->category?->name ?? '-' }}
                    </div>

                    <div>
                        <strong>Proveedor:</strong> {{ $product->supplier?->name ?? '-' }}
                    </div>

                    <div>
                        <strong>Precio:</strong> {{ number_format($product->price, 2) }}
                    </div>

                    <div>
                        <strong>Stock:</strong> {{ $product->stock }}
                    </div>

                    <div>
                        <strong>Stock mínimo:</strong> {{ $product->min_stock }}
                    </div>

                    <div>
                        <strong>Estado:</strong> {{ ucfirst($product->status) }}
                    </div>

                    <div>
                        <strong>Fecha de caducidad:</strong> {{ $product->expiration_date?->format('Y-m-d') ?? '-' }}
                    </div>

                    <div class="sm:col-span-2">
                        <strong>Descripción:</strong>
                        <p>{{ $product->description ?? '-' }}</p>
                    </div>

                    <div class="sm:col-span-2">
                        <strong>Imagen:</strong><br>
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                class="w-48 h-48 object-cover rounded mt-2">
                        @else
                            <p class="text-sm text-gray-600">No hay imagen</p>
                        @endif
                    </div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.products.index') }}"
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                        Volver
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
