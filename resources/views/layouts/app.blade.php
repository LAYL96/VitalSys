<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Librería SweetAlert2 para alertas -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Scripts de Laravel + Tailwind -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Barra de navegación -->
        @include('layouts.navigation')

        <!-- Encabezado dinámico (solo se muestra si la vista define $header) -->
        @isset($header)
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Contenido principal de cada página -->
        <main>
            {{ $slot }}
        </main>
    </div>

    <!-- ============================= -->
    <!-- Mensajes Flash con SweetAlert -->
    <!-- ============================= -->
    <script>
        // Mensaje de error
        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ session('error') }}',
                confirmButtonColor: '#d33'
            });
        @endif

        // Mensaje de éxito
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: '{{ session('success') }}',
                confirmButtonColor: '#3085d6'
            });
        @endif

        // Mensaje de bienvenida al iniciar sesión
        @if (session('welcome'))
            Swal.fire({
                icon: 'info',
                title: '¡Bienvenido!',
                text: '{{ session('welcome') }}',
                confirmButtonColor: '#3085d6',
                timer: 3000, // se cierra en 3 segundos automáticamente
                timerProgressBar: true,
                toast: true, // estilo notificación flotante
                position: 'top-end'
            });
        @endif
    </script>
</body>

</html>
