<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Completar Consulta
        </h2>
    </x-slot>

    <div class="py-8 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">

            {{-- Datos del paciente (solo lectura) --}}
            <div class="grid sm:grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-xs text-gray-500 dark:text-gray-400">Paciente</label>
                    <input
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                        value="{{ $patient->name }} {{ $patient->lastname }}" readonly>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 dark:text-gray-400">Fecha y hora</label>
                    <input
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                        value="{{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }}
                                  {{ \Carbon\Carbon::parse($appointment->time)->format('H:i') }}"
                        readonly>
                </div>
                @if ($patient->dpi)
                    <div>
                        <label class="block text-xs text-gray-500 dark:text-gray-400">DPI</label>
                        <input
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                            value="{{ $patient->dpi }}" readonly>
                    </div>
                @endif
                @if ($patient->birthdate)
                    <div>
                        <label class="block text-xs text-gray-500 dark:text-gray-400">Edad</label>
                        <input
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                            value="{{ \Carbon\Carbon::parse($patient->birthdate)->age }} años" readonly>
                    </div>
                @endif
            </div>

            {{-- Errores --}}
            @if ($errors->any())
                <div class="mb-4 rounded border border-red-300 bg-red-50 text-red-800 px-4 py-2">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li class="text-sm">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('medico.appointments.store', $appointment) }}" autocomplete="off">
                @csrf

                {{-- Notas del paciente al agendar (solo lectura, opcional) --}}
                @if (!empty($appointment->notes))
                    <div class="mb-4">
                        <label class="block text-sm font-semibold mb-1">
                            Notas del paciente (al agendar)
                        </label>
                        <div
                            class="rounded-md border border-gray-200 dark:border-gray-700 p-3 text-sm text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-900">
                            {{ $appointment->notes }}
                        </div>
                    </div>
                @endif

                {{-- Motivo / Síntomas (prellenado con $prefilledReason) --}}
                <div class="mb-4">
                    <label class="block text-sm mb-1">Motivo / Síntomas</label>
                    <textarea name="reason" rows="2"
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                        placeholder="Síntomas relevantes, motivo de consulta...">{{ old('reason', $prefilledReason ?? ($consultation->reason ?? '')) }}</textarea>
                </div>

                {{-- Signos (opcionales) --}}
                <div class="grid sm:grid-cols-4 gap-4 mb-4">
                    <div>
                        <label class="block text-sm mb-1">Temp.</label>
                        <input type="text" name="temperature"
                            value="{{ old('temperature', $consultation->temperature ?? '') }}"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                            placeholder="36.6 °C">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Pulso</label>
                        <input type="text" name="pulse" value="{{ old('pulse', $consultation->pulse ?? '') }}"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                            placeholder="72 lpm">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Presión</label>
                        <input type="text" name="pressure"
                            value="{{ old('pressure', $consultation->pressure ?? '') }}"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                            placeholder="120/80">
                    </div>
                    <div>
                        <label class="block text-sm mb-1">Peso</label>
                        <input type="text" name="weight" value="{{ old('weight', $consultation->weight ?? '') }}"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                            placeholder="70 kg">
                    </div>
                </div>

                {{-- Diagnóstico (requerido) --}}
                <div class="mb-4">
                    <label class="block text-sm mb-1">Diagnóstico <span class="text-red-500">*</span></label>
                    <textarea name="diagnosis" rows="3" required
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                        placeholder="Descripción del diagnóstico...">{{ old('diagnosis', $consultation->diagnosis ?? '') }}</textarea>
                </div>

                {{-- Receta --}}
                <div class="mb-6">
                    <label class="block text-sm mb-1">Receta</label>
                    <textarea name="prescription" rows="3"
                        class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                        placeholder="- Amoxicilina 500mg, 1 cada 8h por 7 días&#10;- Paracetamol 500mg, 1 cada 8h si dolor">{{ old('prescription', $consultation->prescription ?? '') }}</textarea>
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('medico.dashboard') }}"
                        class="px-4 py-2 rounded bg-gray-500 text-white hover:bg-gray-600">Cancelar</a>
                    <button type="submit" class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
                        Guardar consulta y completar
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
