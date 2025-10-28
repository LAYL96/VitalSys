<x-app-layout>
    {{-- =========================
         Header
    ========================== --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Gestión de Usuarios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Flash éxito con fade-out --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.700 x-init="setTimeout(() => show = false, 3000)"
                    class="mb-4 px-4 py-2 bg-green-200 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Barra de acciones / filtros --}}
                    <div class="mb-4 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
                        <a href="{{ route('admin.users.create') }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Crear Usuario
                        </a>

                        {{-- Filtros (opcionales): requiere que el controlador envíe $allRoles (id=>name) --}}
                        @isset($allRoles)
                            <form method="GET" class="flex flex-col sm:flex-row gap-2">
                                <input type="text" name="q" value="{{ request('q') }}"
                                    placeholder="Buscar nombre o email"
                                    class="w-full sm:w-64 border rounded px-2 py-1 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">

                                <select name="role"
                                    class="w-full sm:w-56 border rounded px-2 py-1 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
                                    <option value="">-- Todos los roles --</option>
                                    @foreach ($allRoles as $id => $name)
                                        <option value="{{ $name }}" @selected(request('role') === $name)>{{ $name }}
                                        </option>
                                    @endforeach
                                </select>

                                <button class="px-3 py-1 bg-gray-700 text-white rounded hover:bg-gray-800">
                                    Filtrar
                                </button>
                            </form>
                        @endisset
                    </div>

                    {{-- Tabla --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white dark:bg-gray-900 border dark:border-gray-700">
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
                                        Roles</th>
                                    <th
                                        class="px-6 py-3 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase">
                                        Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse ($users as $user)
                                    <tr class="odd:bg-white even:bg-gray-50 dark:odd:bg-gray-800 dark:even:bg-gray-900">
                                        {{-- Numeración correcta con paginación --}}
                                        <td class="px-6 py-4 text-sm">
                                            {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                                        </td>

                                        <td class="px-6 py-4 text-sm">{{ $user->name }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $user->email }}</td>

                                        {{-- Roles vía Spatie --}}
                                        <td class="px-6 py-4 text-sm">
                                            {{ $user->roles->pluck('name')->join(', ') ?: '—' }}
                                        </td>

                                        <td class="px-6 py-4 text-sm flex flex-wrap gap-2">
                                            <a href="{{ route('admin.users.edit', $user->id) }}"
                                                class="px-2 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">
                                                Editar
                                            </a>

                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                class="delete-user-form inline">
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
                                        <td colspan="5" class="px-6 py-4 text-sm text-center text-gray-500">
                                            No hay usuarios para mostrar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Paginación --}}
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- Confirmación de borrado (requiere que SweetAlert ya esté cargado en tu layout/app) --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-button');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.delete-user-form');
                if (typeof Swal === 'undefined') {
                    // Fallback nativo si no está SweetAlert
                    if (confirm('¿Estás seguro de eliminar este usuario?')) form.submit();
                    return;
                }
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: '¡No podrás revertir esto!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    });
</script>
