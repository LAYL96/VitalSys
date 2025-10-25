<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detalle del Paciente
        </h2>
    </x-slot>

    <div class="py-8 max-w-5xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6 space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <p><strong>DPI:</strong> {{ $patient->dpi }}</p>
                <p><strong>Nombre:</strong> {{ $patient->full_name }}</p>
                <p><strong>Fecha de nacimiento:</strong> {{ $patient->birthdate ?? 'No registrada' }}</p>
                <p><strong>Teléfono:</strong> {{ $patient->phone ?? 'N/A' }}</p>
                <p><strong>Correo electrónico:</strong> {{ $patient->email ?? 'N/A' }}</p>
                <p><strong>Dirección:</strong> {{ $patient->address ?? 'No registrada' }}</p>
            </div>

            <div class="mt-6">
                <a href="{{ route('medico.patients.index') }}"
                    class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition">
                    Volver a la lista de pacientes
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
