<x-app-layout>
    <x-slot name="header">
        @php
            // Puede no haber usuario si esta vista se reutiliza por error siendo público.
            $user = Auth::user();
            $roleName = $user?->getRoleNames()->first(); // Spatie: primer rol
        @endphp

        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            @if ($user)
                Bienvenido {{ $user->name }} @if ($roleName) ({{ $roleName }}) @endif
            @else
                Bienvenido
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Tarjeta principal -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($user)
                        ¡Has iniciado sesión correctamente!
                    @else
                        Estás navegando como invitado.
                    @endif
                </div>
            </div>

            <!-- Bloques visibles solo para el Administrador -->
            @if ($roleName === 'Administrador')
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">

                    <!-- Gestión de Usuarios -->
                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center justify-center space-x-2 px-6 py-4 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition">
                        <span>Gestión de Usuarios</span>
                    </a>

                    <!-- Gestión de Categorías -->
                    <a href="{{ route('admin.categories.index') }}"
                        class="flex items-center justify-center space-x-2 px-6 py-4 bg-green-600 text-white font-semibold rounded-lg shadow hover:bg-green-700 transition">
                        <span>Gestión de Categorías</span>
                    </a>

                    <!-- Gestión de Proveedores -->
                    <a href="{{ route('admin.suppliers.index') }}"
                        class="flex items-center justify-center space-x-2 px-6 py-4 bg-purple-600 text-white font-semibold rounded-lg shadow hover:bg-purple-700 transition">
                        <span>Gestión de Proveedores</span>
                    </a>

                    <!-- Gestión de Productos -->
                    <a href="{{ route('admin.products.index') }}"
                        class="inline-block px-6 py-3 bg-orange-600 text-white font-semibold rounded-lg shadow hover:bg-orange-700 transition">
                        Gestión de Productos
                    </a>

                </div>
            @endif

            <!-- Mensajes para otros roles -->
            @if ($roleName === 'Médico')
                <div class="mt-6 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                    <p class="text-gray-800 dark:text-gray-100">
                        Bienvenido Dr. {{ $user->name }}. Desde este panel podrá revisar sus citas y pacientes asignados.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('medico.dashboard') }}"
                           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                           Ir al Panel del Médico
                        </a>
                    </div>
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
                        Bienvenido {{ $user->name }}. Desde este panel podrá agendar citas y consultar su historial médico.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('cliente.appointments.index') }}"
                           class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                           Mis Citas Médicas
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
