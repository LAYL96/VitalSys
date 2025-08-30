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

                    <!-- Mensaje de éxito -->
                    @if (session('success'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                            class="mb-4 px-4 py-2 bg-green-200 text-green-800 rounded transition duration-500">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.users.store') }}" method="POST">
                        @csrf

                        <!-- Nombre -->
                        <div class="mb-4">
                            <label class="block text-gray-700 dark:text-gray-200">Nombre</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="w-full px-3 py-2 border rounded @error('name') border-red-500 @enderror"
                                required>
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label class="block text-gray-700 dark:text-gray-200">Email</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full px-3 py-2 border rounded @error('email') border-red-500 @enderror"
                                required>
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Contraseña -->
                        <div class="mb-4">
                            <label class="block text-gray-700 dark:text-gray-200">Contraseña</label>
                            <input type="password" name="password"
                                class="w-full px-3 py-2 border rounded @error('password') border-red-500 @enderror"
                                required>
                            @error('password')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
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
                            <select name="role_id"
                                class="w-full px-3 py-2 border rounded @error('role_id') border-red-500 @enderror"
                                required>
                                <option value="">Selecciona un rol</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role_id')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
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
