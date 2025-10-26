<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Registrar Consulta — {{ $patient->full_name ?? $patient->name . ' ' . $patient->lastname }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <form method="POST" action="{{ route('medico.consultations.store', $appointment) }}">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de cita</label>
                        <p class="mt-1 text-gray-900 dark:text-gray-100">
                            {{ \Carbon\Carbon::parse($appointment->date)->format('d/m/Y') }} {{ $appointment->time }}
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Paciente</label>
                        <p class="mt-1 text-gray-900 dark:text-gray-100">
                            {{ $patient->full_name ?? $patient->name . ' ' . $patient->lastname }}
                        </p>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Motivo / Síntomas</label>
                    <textarea name="reason" rows="3"
                        class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">{{ old('reason') }}</textarea>
                    @error('reason')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Diagnóstico *</label>
                    <textarea name="diagnosis" rows="3" required
                        class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">{{ old('diagnosis') }}</textarea>
                    @error('diagnosis')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Receta *</label>
                    <textarea name="prescription" rows="4" required
                        class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100"
                        placeholder="Medicamento — Dosis — Frecuencia — Duración">{{ old('prescription') }}</textarea>
                    @error('prescription')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Temperatura</label>
                        <input type="text" name="temperature" value="{{ old('temperature') }}"
                            class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100"
                            placeholder="36.8 °C">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pulso</label>
                        <input type="text" name="pulse" value="{{ old('pulse') }}"
                            class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100"
                            placeholder="72 lpm">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Presión</label>
                        <input type="text" name="pressure" value="{{ old('pressure') }}"
                            class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100"
                            placeholder="120/80">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Peso</label>
                        <input type="text" name="weight" value="{{ old('weight') }}"
                            class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100"
                            placeholder="70 kg">
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('medico.dashboard') }}"
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancelar</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Guardar
                        Consulta</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
