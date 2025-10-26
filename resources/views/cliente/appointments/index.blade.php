<x-app-layout>
    {{-- ===========================
        Header
    ============================ --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Mis Citas Médicas
        </h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8">

        {{-- Flash de confirmación --}}
        @if (session('success'))
            <div class="mb-4 bg-green-100 text-green-800 px-4 py-2 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- Acciones superiores --}}
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('cliente.appointments.create') }}"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                + Agendar Nueva Cita
            </a>
        </div>

        {{-- Contenido principal --}}
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            @if ($appointments->isEmpty())
                {{-- NO hay citas para el usuario (ni sus dependientes) --}}
                <p class="text-gray-600 dark:text-gray-300 text-center">
                    No tienes citas registradas.
                </p>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-700">
                        <thead class="bg-gray-200 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-2 text-left">Paciente</th>
                                <th class="px-4 py-2 text-left">Médico</th>
                                <th class="px-4 py-2 text-left">Fecha</th>
                                <th class="px-4 py-2 text-left">Hora</th>
                                <th class="px-4 py-2 text-left">Estado</th>
                                <th class="px-4 py-2 text-left">Motivo</th>
                                <th class="px-4 py-2 text-left">Diagnóstico</th>
                                <th class="px-4 py-2 text-left">Receta</th>
                                <th class="px-4 py-2 text-left">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($appointments as $appointment)
                                @php
                                    // Relaciones (pueden venir null si algo no está vinculado)
                                    $patient = $appointment->patient ?? null;
                                    $doctor = $appointment->doctor ?? null;

                                    // Si tienes relación ->consultation en Appointment, úsala; si no existe, no rompe.
                                    $consultation = $appointment->consultation ?? null;

                                    // Futuro para habilitar potencial cancelación
                                    $isFuture = \Carbon\Carbon::parse(
                                        ($appointment->date ?? '') . ' ' . ($appointment->time ?? '00:00'),
                                    )->isFuture();
                                @endphp
                                <tr>
                                    {{-- Paciente (tú o tu dependiente) --}}
                                    <td class="px-4 py-2">
                                        @if ($patient)
                                            {{ $patient->name }} {{ $patient->lastname }}
                                            {{-- Si deseas marcar dependiente, podrías verificar alguna condición adicional aquí --}}
                                        @else
                                            <span class="text-gray-400">N/D</span>
                                        @endif
                                    </td>

                                    {{-- Médico --}}
                                    <td class="px-4 py-2">
                                        {{ $doctor->name ?? 'Médico no asignado' }}
                                    </td>

                                    {{-- Fecha --}}
                                    <td class="px-4 py-2">
                                        @if (!empty($appointment->date))
                                            {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>

                                    {{-- Hora --}}
                                    <td class="px-4 py-2">
                                        {{ $appointment->time ?? '—' }}
                                    </td>

                                    {{-- Estado (badge) --}}
                                    <td class="px-4 py-2">
                                        @switch($appointment->status)
                                            @case('pendiente')
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-yellow-100 text-yellow-800">
                                                    Pendiente
                                                </span>
                                            @break

                                            @case('completada')
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-green-100 text-green-800">
                                                    Completada
                                                </span>
                                            @break

                                            @default
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-800">
                                                    Cancelada
                                                </span>
                                        @endswitch
                                    </td>

                                    {{-- Motivo (notes en la cita) --}}
                                    <td class="px-4 py-2">
                                        @if (!empty($appointment->notes))
                                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                                {{ \Illuminate\Support\Str::limit($appointment->notes, 60) }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-sm">—</span>
                                        @endif
                                    </td>

                                    {{-- Diagnóstico (desde consulta si existe) --}}
                                    <td class="px-4 py-2">
                                        @if ($consultation && !empty($consultation->diagnosis))
                                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                                {{ \Illuminate\Support\Str::limit($consultation->diagnosis, 60) }}
                                            </span>
                                        @elseif (($appointment->status ?? '') === 'pendiente')
                                            <span class="text-gray-500 text-sm">Pendiente de diagnóstico</span>
                                        @else
                                            <span class="text-gray-400 text-sm">No disponible</span>
                                        @endif
                                    </td>

                                    {{-- Receta (desde consulta si existe) --}}
                                    <td class="px-4 py-2">
                                        @if ($consultation && !empty($consultation->prescription))
                                            <span class="text-sm text-gray-700 dark:text-gray-300">
                                                {{ \Illuminate\Support\Str::limit($consultation->prescription, 60) }}
                                            </span>
                                        @elseif (($appointment->status ?? '') === 'pendiente')
                                            <span class="text-gray-500 text-sm">Aún sin receta</span>
                                        @else
                                            <span class="text-gray-400 text-sm">No disponible</span>
                                        @endif
                                    </td>

                                    {{-- Acciones (placeholder de cancelación) --}}
                                    <td class="px-4 py-2">
                                        @if (($appointment->status ?? '') === 'pendiente' && $isFuture)
                                            {{-- Implementa una ruta PATCH/DELETE para cancelar y habilita el botón --}}
                                            <button type="button"
                                                class="px-3 py-1 bg-red-600 text-white rounded opacity-60 cursor-not-allowed"
                                                title="Implementa la ruta de cancelación para habilitar" disabled>
                                                Cancelar
                                            </button>
                                        @else
                                            <span class="text-xs text-gray-400">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Si algún día paginas el resultado (->paginate()), pinta los links aquí: --}}
                {{-- <div class="mt-4">{{ $appointments->links() }}</div> --}}
            @endif
        </div>
    </div>
</x-app-layout>
