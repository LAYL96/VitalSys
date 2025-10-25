<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Agendar Nueva Cita Médica
        </h2>
    </x-slot>

    <div class="py-10 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">

            <form action="{{ route('cliente.appointments.store') }}" method="POST">
                @csrf

                <!-- Seleccionar médico -->
                <div class="mb-4">
                    <label for="doctor_id" class="block font-medium text-gray-700 dark:text-gray-300">
                        Selecciona un Médico
                    </label>
                    <select name="doctor_id" id="doctor_id" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Selecciona --</option>
                        @foreach ($doctors as $doctor)
                            <option value="{{ $doctor->id }}">{{ $doctor->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Fecha -->
                <div class="mb-4">
                    <label for="date" class="block font-medium text-gray-700 dark:text-gray-300">
                        Fecha
                    </label>
                    <input type="date" name="date" id="date" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Hora -->
                <div class="mb-4">
                    <label for="time" class="block font-medium text-gray-700 dark:text-gray-300">
                        Hora
                    </label>
                    <input type="time" name="time" id="time" required
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Notas -->
                <div class="mb-4">
                    <label for="notes" class="block font-medium text-gray-700 dark:text-gray-300">
                        Motivo o Síntomas
                    </label>
                    <textarea name="notes" id="notes" rows="3"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Describe brevemente los síntomas o motivo de la consulta..."></textarea>
                </div>

                <div class="flex justify-end">
                    <a href="{{ route('cliente.appointments.index') }}"
                        class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                        Cancelar
                    </a>
                    <button type="submit"
                        class="ml-3 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        Guardar Cita
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
