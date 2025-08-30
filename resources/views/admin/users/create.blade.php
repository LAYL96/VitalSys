<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Crear Usuario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf

                        <!-- Nombre -->
                        <div class="mb-4">
                            <label class="block text-gray-700 dark:text-gray-200">Nombre</label>
                            <input type="text" name="name" class="w-full px-3 py-2 border rounded" required>
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label class="block text-gray-700 dark:text-gray-200">Email</label>
                            <input type="email" name="email" class="w-full px-3 py-2 border rounded" required>
                        </div>

                        <!-- Contraseña -->
                        <div class="mb-4">
                            <label class="block text-gray-700 dark:text-gray-200">Contraseña</label>
                            <input type="password" name="password" class="w-full px-3 py-2 border rounded" required>
                        </div>

                        <!-- Confirmar Contraseña -->
                        <div class="mb-4">
                            <label class="block text-gray-700 dark:text-gray-200">Confirmar Contraseña</label>
                            <input type="password" name="password_confirmation" class="w-full px-3 py-2 border rounded"
                                required>
                        </div>


                        <!-- Rol -->
                        <div class="mb-4">
                            <label class="block text-gray-700 dark:text-gray-200">Rol</label>
                            <select name="role_id" class="w-full px-3 py-2 border rounded" required>
                                <option value="">Selecciona un rol</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit"
                            class="px-6 py-2 bg-green-600 text-white font-semibold rounded hover:bg-green-700 transition">
                            Crear Usuario
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
