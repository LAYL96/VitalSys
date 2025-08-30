<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Listado de Categorías') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Botón para crear categoría -->
            <div class="mb-4">
                <a href="{{ route('admin.categories.create') }}"
                    class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                    Nueva Categoría
                </a>
            </div>

            <!-- Mensaje de éxito -->
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.1000 x-init="setTimeout(() => show = false, 3000)"
                    class="mb-4 px-4 py-2 bg-green-200 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Tabla de categorías -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="min-w-full bg-white dark:bg-gray-900 border">
                        <thead>
                            <tr class="bg-gray-100 dark:bg-gray-700">
                                <th
                                    class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase">
                                    ID</th>
                                <th
                                    class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase">
                                    Nombre</th>
                                <th
                                    class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase">
                                    Descripción</th>
                                <th
                                    class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase">
                                    Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($categories as $category)
                                <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-800 dark:even:bg-gray-900">
                                    <td class="px-6 py-4 text-sm">{{ $category->id }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $category->name }}</td>
                                    <td class="px-6 py-4 text-sm">{{ $category->description }}</td>
                                    <td class="px-6 py-4 text-sm flex space-x-2">
                                        <a href="{{ route('admin.categories.edit', $category) }}"
                                            class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                            Editar
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                                            class="delete-category-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="px-2 py-1 bg-red-600 text-white rounded hover:bg-red-700 delete-button">
                                                Eliminar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">No hay categorías registradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <!-- Paginación -->
                    <div class="mt-4">
                        {{ $categories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-button');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const form = this.closest('.delete-category-form');

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
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>
