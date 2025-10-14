<x-app-layout>
    <x-slot name="header">
        @php
            $user = Auth::user();
            $roleName = $user->getRoleNames()->first(); // Obtiene el primer rol asignado al usuario
        @endphp

        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Bienvenido {{ $user->name }}
            @if ($roleName)
                ({{ $roleName }})
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Tarjeta principal -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    ¡Has iniciado sesión correctamente!
                </div>
            </div>

            <!-- Botones visibles solo para el Administrador -->
            @if ($roleName === 'Administrador')
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

                    <!-- Gestión de Usuarios -->
                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center justify-center space-x-2 px-6 py-4 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A3 3 0 016 15h12a3 3 0 011.879.804M15 11a3 3 0 10-6 0 3 3 0 006 0z" />
                        </svg>
                        <span>Gestión de Usuarios</span>
                    </a>

                    <!-- Gestión de Categorías -->
                    <a href="{{ route('admin.categories.index') }}"
                        class="flex items-center justify-center space-x-2 px-6 py-4 bg-green-600 text-white font-semibold rounded-lg shadow hover:bg-green-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <span>Gestión de Categorías</span>
                    </a>

                    <!-- Gestión de Proveedores -->
                    <a href="{{ route('admin.suppliers.index') }}"
                        class="flex items-center justify-center space-x-2 px-6 py-4 bg-purple-600 text-white font-semibold rounded-lg shadow hover:bg-purple-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7h18M3 12h18M3 17h18" />
                        </svg>
                        <span>Gestión de Proveedores</span>
                    </a>

                    <!-- Gestión de Productos -->
                    <a href="{{ route('admin.products.index') }}"
                        class="inline-block px-6 py-3 bg-orange-600 text-white font-semibold rounded-lg shadow hover:bg-orange-700 transition">
                        Gestión de Productos
                    </a>

                </div>
            @endif

            <!-- Aquí puedes agregar más secciones para otros roles -->
            @if ($roleName === 'Médico')
                <div class="mt-6 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <p class="text-gray-800 dark:text-gray-100">
                        Bienvenido Dr. {{ $user->name }}. Desde este panel podrá revisar sus citas y pacientes
                        asignados.
                    </p>
                </div>
            @endif

            @if ($roleName === 'Empleado')
                <div class="mt-6 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <p class="text-gray-800 dark:text-gray-100">
                        Bienvenido {{ $user->name }}. Desde este panel podrá gestionar el inventario y la facturación.
                    </p>
                </div>
            @endif

            @if ($roleName === 'Cliente')
                <div class="mt-6 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <p class="text-gray-800 dark:text-gray-100">
                        Bienvenido {{ $user->name }}. Desde este panel podrá agendar citas y consultar su historial
                        médico.
                    </p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
