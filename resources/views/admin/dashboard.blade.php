<x-app-layout :hideSearch="true">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Dashboard Administrador
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Bloques de gestión -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-8">
            <a href="{{ route('admin.users.index') }}"
                class="p-4 bg-blue-600 text-white rounded-lg text-center hover:bg-blue-700 transition">
                Gestionar Usuarios
            </a>
            <a href="{{ route('admin.categories.index') }}"
                class="p-4 bg-green-600 text-white rounded-lg text-center hover:bg-green-700 transition">
                Gestionar Categorías
            </a>
            <a href="{{ route('admin.suppliers.index') }}"
                class="p-4 bg-yellow-600 text-white rounded-lg text-center hover:bg-yellow-700 transition">
                Gestionar Proveedores
            </a>
            <a href="{{ route('admin.products.index') }}"
                class="p-4 bg-red-600 text-white rounded-lg text-center hover:bg-red-700 transition">
                Gestionar Productos
            </a>

            <a href="{{ route('admin.reports.inventory.pdf') }}"
                class="p-4 bg-purple-600 text-white rounded-lg text-center hover:bg-purple-700 transition">
                Descargar Reporte de Inventario (PDF)
            </a>
        </div>

        <!-- ALERTAS DE STOCK BAJO -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 mb-6">
            <h3 class="text-lg font-bold text-red-600 mb-4">Productos con stock bajo</h3>
            @if ($lowStockProducts->isNotEmpty())
                <ul class="list-disc pl-6 text-gray-800 dark:text-gray-200">
                    @foreach ($lowStockProducts as $product)
                        <li class="mb-1">
                            {{ $product->name }} - Stock actual: {{ $product->stock }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-green-600 dark:text-green-400">No hay productos con stock bajo.</p>
            @endif
        </div>

        <!-- ALERTAS DE PRODUCTOS PRÓXIMOS A VENCER -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="text-lg font-bold text-yellow-600 mb-4">Productos próximos a vencer (30 días)</h3>
            @if ($expiringProducts->isNotEmpty())
                <ul class="list-disc pl-6 text-gray-800 dark:text-gray-200">
                    @foreach ($expiringProducts as $product)
                        <li class="mb-1">
                            {{ $product->name }} - Fecha de caducidad: {{ $product->expiration_date->format('d/m/Y') }}
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-green-600 dark:text-green-400">No hay productos próximos a vencer.</p>
            @endif
        </div>
    </div>
</x-app-layout>
