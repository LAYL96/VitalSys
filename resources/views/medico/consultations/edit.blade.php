<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Editar Consulta — {{ $patient->full_name ?? $patient->name . ' ' . $patient->lastname }}
        </h2>
    </x-slot>

    <div class="py-8 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <form method="POST" action="{{ route('medico.consultations.update', $consultation) }}">
                @csrf
                @method('PUT')

                {{-- Campos similares a create, con valores actuales --}}
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Motivo / Síntomas</label>
                    <textarea name="reason" rows="3"
                        class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">{{ old('reason', $consultation->reason) }}</textarea>
                    @error('reason')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Diagnóstico *</label>
                    <textarea name="diagnosis" rows="3" required
                        class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">{{ old('diagnosis', $consultation->diagnosis) }}</textarea>
                    @error('diagnosis')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Receta *</label>
                    <textarea name="prescription" rows="4" required
                        class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">{{ old('prescription', $consultation->prescription) }}</textarea>
                    @error('prescription')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Temperatura</label>
                        <input type="text" name="temperature"
                            value="{{ old('temperature', $consultation->temperature) }}"
                            class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pulso</label>
                        <input type="text" name="pulse" value="{{ old('pulse', $consultation->pulse) }}"
                            class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Presión</label>
                        <input type="text" name="pressure" value="{{ old('pressure', $consultation->pressure) }}"
                            class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Peso</label>
                        <input type="text" name="weight" value="{{ old('weight', $consultation->weight) }}"
                            class="w-full rounded border-gray-300 dark:bg-gray-900 dark:border-gray-700 dark:text-gray-100">
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <a href="{{ route('medico.dashboard') }}"
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Cancelar</a>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Actualizar
                        Consulta</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
