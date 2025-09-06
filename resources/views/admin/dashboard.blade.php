<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Dashboard Administrador
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
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
        </div>
    </div>
</x-app-layout>
