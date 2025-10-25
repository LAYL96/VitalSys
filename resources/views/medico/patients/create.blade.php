<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Registrar Nuevo Paciente
        </h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <form action="{{ route('medico.patients.store') }}" method="POST">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">DPI</label>
                        <input type="text" name="dpi" class="w-full mt-1 border rounded p-2"
                            value="{{ old('dpi') }}" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                        <input type="text" name="name" class="w-full mt-1 border rounded p-2"
                            value="{{ old('name') }}" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Apellido</label>
                        <input type="text" name="lastname" class="w-full mt-1 border rounded p-2"
                            value="{{ old('lastname') }}" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de
                            nacimiento</label>
                        <input type="date" name="birthdate" class="w-full mt-1 border rounded p-2"
                            value="{{ old('birthdate') }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Teléfono</label>
                        <input type="text" name="phone" class="w-full mt-1 border rounded p-2"
                            value="{{ old('phone') }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correo
                            electrónico</label>
                        <input type="email" name="email" class="w-full mt-1 border rounded p-2"
                            value="{{ old('email') }}">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dirección</label>
                        <textarea name="address" rows="3" class="w-full mt-1 border rounded p-2">{{ old('address') }}</textarea>
                    </div>
                </div>

                <div class="mt-6 flex justify-end space-x-3">
                    <a href="{{ route('medico.patients.index') }}"
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancelar</a>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
