<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Lista de Pacientes
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">

        <!-- Botones superiores -->
        <div class="flex justify-between items-center mb-4">
            <!-- Botón Nuevo Paciente -->
            <a href="{{ route('medico.patients.create') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                + Nuevo Paciente
            </a>

            <!-- Botón Volver al Dashboard -->
            <a href="{{ route('medico.dashboard') }}"
                class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-700 transition">
                ← Volver al Panel del Médico
            </a>
        </div>

        <!-- Tabla de Pacientes -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            @if ($patients->count() > 0)
                <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                    <thead class="bg-gray-200 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">DPI</th>
                            <th class="px-4 py-2 text-left">Nombre</th>
                            <th class="px-4 py-2 text-left">Teléfono</th>
                            <th class="px-4 py-2 text-left">Correo</th>
                            <th class="px-4 py-2 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($patients as $patient)
                            <tr>
                                <td class="px-4 py-2">{{ $patient->dpi }}</td>
                                <td class="px-4 py-2">{{ $patient->full_name }}</td>
                                <td class="px-4 py-2">{{ $patient->phone ?? 'N/A' }}</td>
                                <td class="px-4 py-2">{{ $patient->email ?? 'N/A' }}</td>
                                <td class="px-4 py-2 flex space-x-2">
                                    <a href="{{ route('medico.patients.show', $patient) }}"
                                        class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">Ver</a>
                                    <a href="{{ route('medico.patients.edit', $patient) }}"
                                        class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Editar</a>
                                    <form action="{{ route('medico.patients.destroy', $patient) }}" method="POST"
                                        onsubmit="return confirm('¿Eliminar este paciente?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $patients->links() }}
                </div>
            @else
                <p class="text-gray-600 dark:text-gray-300">No hay pacientes registrados.</p>
            @endif
        </div>
    </div>
</x-app-layout>
