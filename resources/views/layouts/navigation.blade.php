@php
    use Illuminate\Support\Facades\Auth;
    $user = Auth::user();
    $role = $user?->getRoleNames()->first(); // Obtenemos el rol con Spatie
@endphp

<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">

            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="font-bold text-xl text-gray-800 dark:text-gray-200">
                    VitalSys
                </a>
            </div>

            <!-- Buscador (solo visible si NO es Administrador) -->
            @if (!$role || $role !== 'Administrador')
                <div class="flex-1 flex justify-center items-center px-2">
                    <form action="{{ route('products.public') }}" method="GET" class="w-full max-w-lg">
                        <input type="text" name="search" placeholder="Buscar producto..."
                            class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                    </form>
                </div>
            @endif

            <!-- Menú derecho -->
            <div class="flex items-center space-x-4">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300">
                                <!-- Mostrar nombre según rol -->
                                <div>
                                    @if ($role === 'Médico')
                                        Dr. {{ $user->name }}
                                    @else
                                        {{ $user->name }}
                                    @endif
                                </div>
                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Opciones dinámicas según rol -->
                            @switch($role)
                                @case('Administrador')
                                    <x-dropdown-link :href="route('admin.dashboard')">Panel de Administración</x-dropdown-link>
                                @break

                                @case('Empleado')
                                    <x-dropdown-link :href="route('empleado.dashboard')">Gestión de Inventario</x-dropdown-link>
                                @break

                                @case('Médico')
                                    <x-dropdown-link :href="route('medico.dashboard')">Mis Citas</x-dropdown-link>
                                @break

                                @case('Cliente')
                                    <x-dropdown-link :href="route('cliente.dashboard')">Mis Citas Médicas</x-dropdown-link>
                                @break
                            @endswitch

                            <!-- Perfil -->
                            <x-dropdown-link :href="route('profile.edit')">Perfil</x-dropdown-link>

                            <!-- Cerrar sesión -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    Cerrar sesión
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <!-- Si no está logueado -->
                    <a href="{{ route('login') }}"
                        class="px-4 py-2 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">Login</a>
                    <a href="{{ route('register') }}"
                        class="px-4 py-2 text-sm text-white bg-green-600 rounded hover:bg-green-700">Registro</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
