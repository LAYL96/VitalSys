<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Tarjeta principal -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __('¡Has iniciado sesión correctamente!') }}
                </div>
            </div>

            <!-- Botón para Administrador -->
            @if (Auth::user()->role->name === 'Administrador')
                <div class="mt-6">
                    <a href="{{ route('admin.users') }}"
                        class="inline-block px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition">
                        Gestión de Usuarios
                    </a>
                </div>
            @endif

            <!-- Aquí puedes agregar botones o secciones adicionales para otros roles -->
        </div>
    </div>
</x-app-layout>
