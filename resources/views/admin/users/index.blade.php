<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestión de Usuarios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Mensaje de éxito -->
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                    class="mb-4 px-4 py-2 bg-green-200 text-green-800 rounded transition duration-500">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Botón para crear usuario -->
                    <div class="mb-4">
                        <a href="{{ route('admin.users.create') }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Crear Usuario
                        </a>
                    </div>

                    <!-- Tabla de usuarios -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-900 border">
                            <thead>
                                <tr class="bg-gray-100 dark:bg-gray-700">
                                    <th
                                        class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">
                                        #</th>
                                    <th
                                        class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">
                                        Nombre</th>
                                    <th
                                        class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">
                                        Email</th>
                                    <th
                                        class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">
                                        Rol</th>
                                    <th
                                        class="px-6 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-200">
                                        Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 text-sm">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $user->name }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $user->email }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $user->role->name }}</td>
                                        <td class="px-6 py-4 text-sm">
                                            <!-- Aquí puedes agregar botones para editar o eliminar -->
                                            <a href="{{ route('admin.users.edit', $user->id) }}"
                                                class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Editar</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
