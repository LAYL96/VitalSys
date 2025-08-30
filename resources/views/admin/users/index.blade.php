<x-app-layout>
    <!-- Header de la página -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestión de Usuarios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Mensaje de éxito con fade-out automático -->
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.1000 x-init="setTimeout(() => show = false, 3000)"
                    class="mb-4 px-4 py-2 bg-green-200 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Botón para crear usuario -->
                    <div class="mb-4 flex justify-between items-center">
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
                                        class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase">
                                        #</th>
                                    <th
                                        class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase">
                                        Nombre</th>
                                    <th
                                        class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase">
                                        Email</th>
                                    <th
                                        class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase">
                                        Rol</th>
                                    <th
                                        class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase">
                                        Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($users as $user)
                                    <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-800 dark:even:bg-gray-900">
                                        <td class="px-6 py-4 text-sm">{{ $loop->iteration }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $user->name }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $user->email }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $user->role->name }}</td>
                                        <td class="px-6 py-4 text-sm flex space-x-2">
                                            <!-- Botón Editar -->
                                            <a href="{{ route('admin.users.edit', $user->id) }}"
                                                class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                                Editar
                                            </a>

                                            <!-- Botón Eliminar con confirmación emergente -->
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                class="delete-user-form">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button"
                                                    class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 delete-button">
                                                    Eliminar
                                                </button>
                                            </form>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-button');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.delete-user-form');

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Envía el formulario solo si confirma
                    }
                });
            });
        });
    });
</script>
