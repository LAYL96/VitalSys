<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Detalle del Proveedor</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-lg mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p><strong>ID:</strong> {{ $supplier->id }}</p>
                <p><strong>Nombre:</strong> {{ $supplier->name }}</p>
                <p><strong>Información de contacto:</strong> {{ $supplier->contact_info ?? '-' }}</p>

                <a href="{{ route('admin.suppliers.index') }}" class="mt-4 inline-block text-blue-500">Volver</a>
            </div>
        </div>
    </div>
</x-app-layout>
