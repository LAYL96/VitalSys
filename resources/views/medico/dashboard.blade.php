<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Panel del Médico - Dr. {{ Auth::user()->name }}
            </h2>

            <!-- Botón para ver pacientes -->
            <a href="{{ route('medico.patients.index') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 100 7.292M16 12v2m0 4h.01M8 12v2m0 4h.01M12 14v6" />
                </svg>
                Ver Pacientes
            </a>
        </div>
    </x-slot>

    <div class="py-10 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

        <!-- Mensaje de confirmación -->
        @if (session('success'))
            <div class="mb-4 bg-green-100 text-green-800 px-4 py-2 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Citas Pendientes -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-lg font-bold text-blue-600 mb-4">Citas Pendientes</h3>
            @if ($pendingAppointments->isEmpty())
                <p class="text-gray-600 dark:text-gray-300">No tienes citas pendientes.</p>
            @else
                <table class="min-w-full border border-gray-300 rounded-lg">
                    <thead class="bg-gray-200 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Paciente</th>
                            <th class="px-4 py-2 text-left">Fecha</th>
                            <th class="px-4 py-2 text-left">Hora</th>
                            <th class="px-4 py-2 text-left">Notas</th>
                            <th class="px-4 py-2 text-left">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendingAppointments as $appointment)
                            <tr class="border-t dark:border-gray-600">
                                <td class="px-4 py-2">{{ $appointment->patient->name }}
                                    {{ $appointment->patient->lastname }}</td>
                                <td class="px-4 py-2">{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}
                                </td>
                                <td class="px-4 py-2">{{ $appointment->time }}</td>
                                <td class="px-4 py-2">{{ $appointment->notes ?? 'Sin notas' }}</td>
                                <td class="px-4 py-2 space-x-2">
                                    <!-- Botón Completar -->
                                    <form action="{{ route('medico.appointments.updateStatus', $appointment->id) }}"
                                        method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="completada">
                                        <button type="submit"
                                            class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700 transition">
                                            Completar
                                        </button>
                                    </form>

                                    <!-- Botón Cancelar -->
                                    <form action="{{ route('medico.appointments.updateStatus', $appointment->id) }}"
                                        method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="cancelada">
                                        <button type="submit"
                                            class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 transition">
                                            Cancelar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <!-- Citas Completadas -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-lg font-bold text-green-600 mb-4">Citas Completadas</h3>
            @if ($completedAppointments->isEmpty())
                <p class="text-gray-600 dark:text-gray-300">No tienes citas completadas.</p>
            @else
                <ul class="list-disc pl-6 text-gray-700 dark:text-gray-300">
                    @foreach ($completedAppointments as $appointment)
                        <li>
                            {{ $appointment->patient->name }} {{ $appointment->patient->lastname }} -
                            {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}
                            ({{ $appointment->time }})
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>

        <!-- Citas Canceladas -->
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
            <h3 class="text-lg font-bold text-red-600 mb-4">Citas Canceladas</h3>
            @if ($canceledAppointments->isEmpty())
                <p class="text-gray-600 dark:text-gray-300">No tienes citas canceladas.</p>
            @else
                <ul class="list-disc pl-6 text-gray-700 dark:text-gray-300">
                    @foreach ($canceledAppointments as $appointment)
                        <li>
                            {{ $appointment->patient->name }} {{ $appointment->patient->lastname }} -
                            {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}
                            ({{ $appointment->time }})
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-app-layout>
