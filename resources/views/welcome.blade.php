<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'VitalSys') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-100 font-sans" x-data="{ openDropdown: false, openMobile: false }">

    <!-- HEADER -->
    <header class="bg-white shadow py-4 px-6">
        <div class="max-w-7xl mx-auto flex justify-between items-center">

            <!-- Logo -->
            <a href="{{ route('home') }}" class="text-2xl font-bold text-gray-800">VitalSys</a>

            <!-- Buscador -->
            <form action="{{ route('products.public') }}" method="GET" class="hidden sm:flex w-1/3 max-w-md">
                <input type="text" name="search" placeholder="Buscar productos..." value="{{ request('search') }}"
                    class="flex-1 px-4 py-2 border rounded-l focus:outline-none focus:ring focus:border-blue-500">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-r hover:bg-blue-700 transition">Buscar</button>
            </form>

            <!-- Desktop Auth -->
            <div class="hidden sm:flex items-center space-x-2">
                @guest
                    <a href="{{ route('login') }}"
                        class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-900 transition">Login</a>
                    <a href="{{ route('register') }}"
                        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Registrarse</a>
                @endguest

                @auth
                    @php
                        $user = auth()->user();
                        $role = $user->role->name ?? null;
                    @endphp
                    <div class="relative" @click.away="openDropdown = false">
                        <button @click="openDropdown = !openDropdown"
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none">
                            {{ $user->name }}
                            <svg class="ml-2 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>

                        <div x-show="openDropdown" x-transition
                            class="absolute right-0 mt-2 w-48 bg-white border rounded-md shadow-lg z-50">
                            @if ($role && $role !== 'Cliente')
                                @if ($role === 'Administrador')
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Panel Admin</a>
                                @elseif ($role === 'Empleado')
                                    <a href="{{ route('empleado.dashboard') }}"
                                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Inventario</a>
                                @elseif ($role === 'Médico')
                                    <a href="{{ route('medico.dashboard') }}"
                                        class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Citas Médicas</a>
                                @endif
                            @endif

                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Perfil</a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    Cerrar sesión
                                </button>
                            </form>
                        </div>
                    </div>
                @endauth
            </div>

            <!-- Mobile Hamburger -->
            <div class="sm:hidden flex items-center">
                <button @click="openMobile = !openMobile" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': openMobile, 'inline-flex': !openMobile }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !openMobile, 'inline-flex': openMobile }" class="hidden"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>

        <!-- Mobile Menu -->
        <div x-show="openMobile" x-transition class="sm:hidden mt-2 space-y-1 px-2 pb-3">
            <!-- Buscador Mobile -->
            <form action="{{ route('products.public') }}" method="GET" class="flex w-full mb-2">
                <input type="text" name="search" placeholder="Buscar productos..." value="{{ request('search') }}"
                    class="flex-1 px-4 py-2 border rounded-l focus:outline-none focus:ring focus:border-blue-500">
                <button type="submit"
                    class="px-4 py-2 bg-blue-600 text-white rounded-r hover:bg-blue-700 transition">Buscar</button>
            </form>

            @guest
                <a href="{{ route('login') }}" class="block px-4 py-2 text-gray-700 rounded hover:bg-gray-100">Login</a>
                <a href="{{ route('register') }}"
                    class="block px-4 py-2 text-gray-700 rounded hover:bg-gray-100">Registrarse</a>
            @endguest

            @auth
                @if ($role && $role !== 'Cliente')
                    @if ($role === 'Administrador')
                        <a href="{{ route('admin.dashboard') }}"
                            class="block px-4 py-2 text-gray-700 rounded hover:bg-gray-100">Panel Admin</a>
                    @elseif ($role === 'Empleado')
                        <a href="{{ route('empleado.dashboard') }}"
                            class="block px-4 py-2 text-gray-700 rounded hover:bg-gray-100">Inventario</a>
                    @elseif ($role === 'Médico')
                        <a href="{{ route('medico.dashboard') }}"
                            class="block px-4 py-2 text-gray-700 rounded hover:bg-gray-100">Citas Médicas</a>
                    @endif
                @endif

                <a href="{{ route('profile.edit') }}"
                    class="block px-4 py-2 text-gray-700 rounded hover:bg-gray-100">Perfil</a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 rounded hover:bg-gray-100">
                        Cerrar sesión
                    </button>
                </form>
            @endauth
        </div>
    </header>

    <!-- HERO SECTION -->
    <section class="relative bg-blue-600 text-white">
        <div class="max-w-7xl mx-auto px-6 py-24 flex flex-col md:flex-row items-center justify-between">
            <div class="md:w-1/2 mb-8 md:mb-0">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">Bienvenido a VitalSys</h1>
                <p class="text-lg md:text-xl mb-6">Encuentra los productos que necesitas de manera rápida y segura.</p>
                <a href="{{ route('products.public') }}"
                    class="px-6 py-3 bg-white text-blue-600 font-semibold rounded hover:bg-gray-100 transition">
                    Explorar Productos
                </a>
            </div>
            <div class="md:w-1/2">
                <img src="https://images.unsplash.com/photo-1598300051914-6f3d9e0b1e29?auto=format&fit=crop&w=800&q=80"
                    alt="Productos VitalSys" class="rounded-lg shadow-lg">
            </div>
        </div>
        <div class="absolute inset-0 bg-black opacity-10"></div>
    </section>

    <!-- PRODUCTOS DESTACADOS -->
    <main class="max-w-7xl mx-auto py-12 px-6">
        <h2 class="text-3xl font-semibold mb-8 text-center">Productos Disponibles</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($products as $product)
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    @if ($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                            class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500">
                            Sin Imagen
                        </div>
                    @endif
                    <div class="p-4">
                        <h3 class="font-semibold text-lg">{{ $product->name }}</h3>
                        <p class="text-gray-600 text-sm mb-2">{{ Str::limit($product->description, 80) }}</p>
                        <p class="font-bold text-blue-600">Q.{{ $product->price }}</p>
                        <a href="{{ route('products.show', $product->id) }}"
                            class="mt-2 inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Ver Detalle
                        </a>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-center text-gray-500">No se encontraron productos.</p>
            @endforelse
        </div>
    </main>

    <!-- FOOTER -->
    <footer class="bg-gray-800 text-white py-6 mt-12">
        <div class="max-w-7xl mx-auto px-6 text-center">
            &copy; {{ date('Y') }} VitalSys. Todos los derechos reservados.
        </div>
    </footer>

    <!-- Mensajes Flash con SweetAlert -->
    <script>
        @if (session('welcome'))
            Swal.fire({
                icon: 'info',
                title: '¡Bienvenido!',
                text: '{{ session('welcome') }}',
                confirmButtonColor: '#3085d6',
                timer: 3000,
                timerProgressBar: true,
                toast: true,
                position: 'top-end'
            });
        @endif
    </script>

</body>

</html>
