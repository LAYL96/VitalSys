<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Editar Proveedor</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-lg mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                @if ($errors->any())
                    <div id="alert-error"
                        class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.suppliers.update', $supplier) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-gray-700">Nombre</label>
                        <input type="text" name="name" value="{{ old('name', $supplier->name) }}"
                            class="w-full border px-3 py-2 rounded">
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700">Información de contacto</label>
                        <textarea name="contact_info" class="w-full border px-3 py-2 rounded">{{ old('contact_info', $supplier->contact_info) }}</textarea>
                    </div>
                    <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Actualizar</button>
                    <a href="{{ route('admin.suppliers.index') }}" class="ml-2 text-gray-700">Cancelar</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        setTimeout(() => {
            const errorAlert = document.getElementById('alert-error');
            if (errorAlert) errorAlert.remove();
        }, 5000);
    </script>
</x-app-layout>
