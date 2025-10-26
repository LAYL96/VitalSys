<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Detalle de Consulta — {{ $patient->full_name ?? $patient->name . ' ' . $patient->lastname }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-500">Fecha/Hora</p>
                    <p class="font-semibold">
                        {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }} {{ $appointment->time }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Paciente</p>
                    <p class="font-semibold">{{ $patient->full_name ?? $patient->name . ' ' . $patient->lastname }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="font-bold mb-2">Motivo / Síntomas</h3>
            <p class="whitespace-pre-line">{{ $consultation->reason ?? '—' }}</p>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="font-bold mb-2">Diagnóstico</h3>
            <p class="whitespace-pre-line">{{ $consultation->diagnosis }}</p>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="font-bold mb-2">Receta</h3>
            <p class="whitespace-pre-line">{{ $consultation->prescription }}</p>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h3 class="font-bold mb-2">Signos Vitales</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div><span class="text-gray-500 text-sm">Temperatura:</span> {{ $consultation->temperature ?? '—' }}
                </div>
                <div><span class="text-gray-500 text-sm">Pulso:</span> {{ $consultation->pulse ?? '—' }}</div>
                <div><span class="text-gray-500 text-sm">Presión:</span> {{ $consultation->pressure ?? '—' }}</div>
                <div><span class="text-gray-500 text-sm">Peso:</span> {{ $consultation->weight ?? '—' }}</div>
            </div>
        </div>

        <div class="flex justify-end">
            <a href="{{ route('medico.consultations.edit', $consultation) }}"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Editar Consulta</a>
        </div>
    </div>
</x-app-layout>
