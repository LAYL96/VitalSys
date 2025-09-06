@php
    $user = Auth::user();
    $role = $user->role->name ?? null; // null si no hay usuario logueado
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

            <!-- Buscador (solo para público y usuarios) -->
            <div class="flex-1 flex justify-center items-center px-2">
                <form action="{{ route('products.public') }}" method="GET" class="w-full max-w-lg">
                    <input type="text" name="search" placeholder="Buscar producto..."
                        class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:border-blue-300">
                </form>
            </div>

            <!-- Links de login/registro o usuario autenticado -->
            <div class="flex items-center space-x-4">
                @auth
                    <!-- Usuario logueado -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300">
                                <div>{{ $user->name }}</div>
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
                            @if ($role)
                                @if ($role === 'Administrador')
                                    <x-dropdown-link :href="route('admin.users.index')">Administración</x-dropdown-link>
                                @elseif ($role === 'Empleado')
                                    <x-dropdown-link :href="route('empleado.dashboard')">Inventario</x-dropdown-link>
                                @elseif ($role === 'Médico')
                                    <x-dropdown-link :href="route('medico.dashboard')">Citas Médicas</x-dropdown-link>
                                @elseif ($role === 'Cliente')
                                    <x-dropdown-link :href="route('cliente.dashboard')">Mis Citas</x-dropdown-link>
                                @endif
                            @endif

                            <x-dropdown-link :href="route('profile.edit')">Perfil</x-dropdown-link>

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
                    <!-- Invitado -->
                    <a href="{{ route('login') }}"
                        class="px-4 py-2 text-sm text-white bg-blue-600 rounded hover:bg-blue-700">Login</a>
                    <a href="{{ route('register') }}"
                        class="px-4 py-2 text-sm text-white bg-green-600 rounded hover:bg-green-700">Registro</a>
                @endauth
            </div>

            <!-- Hamburger para móvil -->
            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = !open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden px-4 pb-3">
        @auth
            <div class="pt-2 pb-3 space-y-1">
                @if ($role)
                    @if ($role === 'Administrador')
                        <x-responsive-nav-link :href="route('admin.users.index')">Administración</x-responsive-nav-link>
                    @elseif ($role === 'Empleado')
                        <x-responsive-nav-link :href="route('empleado.dashboard')">Inventario</x-responsive-nav-link>
                    @elseif ($role === 'Médico')
                        <x-responsive-nav-link :href="route('medico.dashboard')">Citas Médicas</x-responsive-nav-link>
                    @elseif ($role === 'Cliente')
                        <x-responsive-nav-link :href="route('cliente.dashboard')">Mis Citas</x-responsive-nav-link>
                    @endif
                @endif
                <x-responsive-nav-link :href="route('profile.edit')">Perfil</x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        Cerrar sesión
                    </x-responsive-nav-link>
                </form>
            </div>
        @else
            <div class="pt-2 pb-3 space-y-1">
                <x-responsive-nav-link :href="route('login')">Login</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('register')">Registro</x-responsive-nav-link>
            </div>
        @endauth
    </div>
</nav>
