<x-app-layout :hideSearch="true">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200">
            Panel del Médico
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <div class="p-6 bg-blue-600 text-white rounded-lg shadow text-center">
                <h3 class="text-lg font-semibold">Pacientes Registrados</h3>
                <p class="text-3xl font-bold mt-2">{{ $totalPatients }}</p>
            </div>

            <div class="p-6 bg-green-600 text-white rounded-lg shadow text-center">
                <h3 class="text-lg font-semibold">Citas Pendientes Hoy</h3>
                <p class="text-3xl font-bold mt-2">{{ $todayAppointments }}</p>
            </div>

            <div class="p-6 bg-yellow-600 text-white rounded-lg shadow text-center">
                <h3 class="text-lg font-semibold">Citas Totales</h3>
                <p class="text-3xl font-bold mt-2">{{ $totalAppointments }}</p>
            </div>
        </div>
    </div>
</x-app-layout>
