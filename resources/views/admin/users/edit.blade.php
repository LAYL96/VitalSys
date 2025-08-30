<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Editar Usuario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-gray-700 dark:text-gray-200">Nombre</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                class="w-full px-3 py-2 border rounded" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 dark:text-gray-200">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                class="w-full px-3 py-2 border rounded" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 dark:text-gray-200">Rol</label>
                            <select name="role_id" class="w-full px-3 py-2 border rounded" required>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}"
                                        @if ($user->role_id == $role->id) selected @endif>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 dark:text-gray-200">Nueva Contraseña (opcional)</label>
                            <input type="password" name="password" class="w-full px-3 py-2 border rounded">
                            <small class="text-gray-500">Solo completa si quieres cambiar la contraseña.</small>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 dark:text-gray-200">Confirmar Nueva Contraseña</label>
                            <input type="password" name="password_confirmation" class="w-full px-3 py-2 border rounded">
                        </div>


                        <div class="flex justify-end">
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                                Actualizar Usuario
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
