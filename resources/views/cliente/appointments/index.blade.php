<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Mis Citas Médicas
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">

        <!-- Mensaje de confirmación -->
        @if (session('success'))
            <div class="mb-4 bg-green-100 text-green-800 px-4 py-2 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex justify-between mb-6">
            <a href="{{ route('cliente.appointments.create') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                + Agendar Nueva Cita
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            @if ($appointments->isEmpty())
                <p class="text-gray-600 dark:text-gray-300 text-center">
                    No tienes citas registradas.
                </p>
            @else
                <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                    <thead class="bg-gray-200 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Médico</th>
                            <th class="px-4 py-2 text-left">Fecha</th>
                            <th class="px-4 py-2 text-left">Hora</th>
                            <th class="px-4 py-2 text-left">Estado</th>
                            <th class="px-4 py-2 text-left">Diagnóstico</th>
                            <th class="px-4 py-2 text-left">Receta</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($appointments as $appointment)
                            <tr>
                                <td class="px-4 py-2">{{ $appointment->doctor->name ?? 'Médico no asignado' }}</td>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-2">{{ $appointment->time }}</td>
                                <td class="px-4 py-2 capitalize">
                                    @if ($appointment->status === 'pendiente')
                                        <span class="text-yellow-600 font-semibold">Pendiente</span>
                                    @elseif ($appointment->status === 'completada')
                                        <span class="text-green-600 font-semibold">Completada</span>
                                    @else
                                        <span class="text-red-600 font-semibold">Cancelada</span>
                                    @endif
                                </td>

                                <td class="px-4 py-2">
                                    @if ($appointment->diagnosis)
                                        {{ $appointment->diagnosis }}
                                    @elseif ($appointment->status === 'pendiente')
                                        <span class="text-gray-500 text-sm">Pendiente de diagnóstico</span>
                                    @else
                                        <span class="text-gray-400 text-sm">No disponible</span>
                                    @endif
                                </td>

                                <td class="px-4 py-2">
                                    @if ($appointment->prescription)
                                        {{ $appointment->prescription }}
                                    @elseif ($appointment->status === 'pendiente')
                                        <span class="text-gray-500 text-sm">Aún sin receta</span>
                                    @else
                                        <span class="text-gray-400 text-sm">No disponible</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</x-app-layout>
