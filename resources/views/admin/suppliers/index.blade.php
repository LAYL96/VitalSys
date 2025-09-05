<x-app-layout>
    <!-- Header de la página -->
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestión de Proveedores') }}
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

                    <!-- Botón para crear proveedor -->
                    <div class="mb-4 flex justify-between items-center">
                        <a href="{{ route('admin.suppliers.create') }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Crear Proveedor
                        </a>

                        <!-- Formulario de búsqueda -->
                        <form method="GET" action="{{ route('admin.suppliers.index') }}" class="flex space-x-2">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Buscar por nombre o contacto"
                                class="px-4 py-2 border rounded w-full sm:w-64 dark:bg-gray-700 dark:text-gray-100">
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                Buscar
                            </button>
                            <a href="{{ route('admin.suppliers.index') }}"
                                class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                                Limpiar
                            </a>
                        </form>
                    </div>

                    <!-- Tabla de proveedores -->
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
                                        Contacto</th>
                                    <th
                                        class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase">
                                        Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach ($suppliers as $supplier)
                                    <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-800 dark:even:bg-gray-900">
                                        <td class="px-6 py-4 text-sm">
                                            {{ $loop->iteration + ($suppliers->currentPage() - 1) * $suppliers->perPage() }}
                                        </td>
                                        <td class="px-6 py-4 text-sm">{{ $supplier->name }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $supplier->contact_info }}</td>
                                        <td class="px-6 py-4 text-sm flex space-x-2">
                                            <!-- Botón Ver -->
                                            <a href="{{ route('admin.suppliers.show', $supplier->id) }}"
                                                class="px-2 py-1 bg-gray-600 text-white rounded hover:bg-gray-700">
                                                Ver
                                            </a>

                                            <!-- Botón Editar -->
                                            <a href="{{ route('admin.suppliers.edit', $supplier->id) }}"
                                                class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                                Editar
                                            </a>

                                            <!-- Botón Eliminar con confirmación -->
                                            <form action="{{ route('admin.suppliers.destroy', $supplier->id) }}"
                                                method="POST" class="delete-supplier-form">
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
                        {{ $suppliers->links() }}
                    </div>

                    <!-- Contador de registros -->
                    <p class="text-sm text-gray-600 mt-2">
                        Mostrando {{ $suppliers->firstItem() }} a {{ $suppliers->lastItem() }} de
                        {{ $suppliers->total() }} proveedores
                    </p>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<!-- Script de confirmación de eliminación -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-button');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('form');

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
