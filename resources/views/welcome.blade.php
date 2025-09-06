<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'VitalSys') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-100 font-sans">

    <!-- HEADER -->
    <header class="bg-white shadow py-4 px-6 flex justify-between items-center">
        <!-- Logo / Nombre -->
        <a href="{{ route('home') }}" class="text-2xl font-bold text-gray-800">VitalSys</a>

        <!-- Buscador -->
        <form action="{{ route('products.public') }}" method="GET" class="flex w-1/3 max-w-md">
            <input type="text" name="search" placeholder="Buscar productos..." value="{{ request('search') }}"
                class="flex-1 px-4 py-2 border rounded-l focus:outline-none focus:ring focus:border-blue-500">
            <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-r hover:bg-blue-700 transition">Buscar</button>
        </form>

        <!-- Login / Registro / Perfil / Dashboard -->
        <div class="space-x-2">
            @guest
                <!-- Solo usuarios no autenticados -->
                <a href="{{ route('login') }}"
                    class="px-4 py-2 bg-gray-800 text-white rounded hover:bg-gray-900 transition">Login</a>
                <a href="{{ route('register') }}"
                    class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Registrarse</a>
            @endguest

            @auth
                <!-- Solo usuarios autenticados -->
                @if (auth()->user()->role->name === 'Administrador')
                    <a href="{{ route('admin.dashboard') }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Panel Admin</a>
                @else
                    <a href="{{ route('profile.edit') }}"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">Mi Perfil</a>
                @endif
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
                    class="px-6 py-3 bg-white text-blue-600 font-semibold rounded hover:bg-gray-100 transition">Explorar
                    Productos</a>
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
                        <p class="font-bold text-blue-600">${{ $product->price }}</p>
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
