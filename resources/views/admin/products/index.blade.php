<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestión de Productos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Mensaje de éxito -->
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.1000 x-init="setTimeout(() => show = false, 3000)"
                    class="mb-4 px-4 py-2 bg-green-200 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Botón crear y buscador -->
                    <div class="mb-4 flex justify-between items-center">
                        <a href="{{ route('admin.products.create') }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Crear Producto
                        </a>

                        <form method="GET" action="{{ route('admin.products.index') }}" class="flex space-x-2">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Buscar por nombre, SKU o categoría"
                                class="px-4 py-2 border rounded w-full sm:w-64 dark:bg-gray-700 dark:text-gray-100">
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                Buscar
                            </button>
                            <a href="{{ route('admin.products.index') }}"
                                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                                Limpiar
                            </a>
                        </form>
                    </div>

                    <!-- Tabla de productos -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-900 border">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700">
                                    <th
                                        class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase">
                                        #</th>
                                    <th
                                        class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase">
                                        Nombre</th>
                                    <th
                                        class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase">
                                        SKU</th>
                                    <th
                                        class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase">
                                        Categoría</th>
                                    <th
                                        class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase">
                                        Proveedor</th>
                                    <th
                                        class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase">
                                        Precio</th>
                                    <th
                                        class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase">
                                        Stock</th>
                                    <th
                                        class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase">
                                        Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($products as $product)
                                    <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-800 dark:even:bg-gray-900">
                                        <td class="px-6 py-4 text-sm">
                                            {{ $loop->iteration + ($products->currentPage() - 1) * $products->perPage() }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">{{ $product->name }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $product->sku }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $product->category?->name ?? '-' }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $product->supplier?->name ?? '-' }}</td>
                                        <td class="px-6 py-4 text-sm">{{ number_format($product->price, 2) }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $product->stock }}</td>
                                        <td class="px-6 py-4 text-sm flex space-x-2">
                                            <a href="{{ route('admin.products.show', $product->id) }}"
                                                class="px-2 py-1 bg-gray-600 text-white rounded hover:bg-gray-700">
                                                Ver
                                            </a>
                                            <a href="{{ route('admin.products.edit', $product->id) }}"
                                                class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                                Editar
                                            </a>
                                            <form action="{{ route('admin.products.destroy', $product->id) }}"
                                                method="POST" class="delete-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 delete-button">
                                                    Eliminar
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="mt-4">
                        {{ $products->links() }}
                    </div>
                    <p class="text-sm text-gray-600 mt-2">
                        Mostrando {{ $products->firstItem() }} a {{ $products->lastItem() }} de
                        {{ $products->total() }} productos
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
