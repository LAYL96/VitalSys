<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Crear Producto') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">

                <!-- Validación de errores -->
                @if ($errors->any())
                    <div class="mb-4 px-4 py-2 bg-red-200 text-red-800 rounded">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                        <div>
                            <label class="block mb-1">Nombre</label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                        </div>

                        <div>
                            <label class="block mb-1">SKU</label>
                            <input type="text" name="sku" value="{{ old('sku') }}"
                                class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                        </div>

                        <div>
                            <label class="block mb-1">Categoría</label>
                            <select name="category_id"
                                class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                                <option value="">-- Seleccionar --</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                        {{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block mb-1">Proveedor</label>
                            <select name="supplier_id"
                                class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                                <option value="">-- Seleccionar --</option>
                                @foreach ($suppliers as $supplier)
                                    <option value="{{ $supplier->id }}" @selected(old('supplier_id') == $supplier->id)>
                                        {{ $supplier->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block mb-1">Precio</label>
                            <input type="number" step="0.01" name="price" value="{{ old('price') }}"
                                class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                        </div>

                        <div>
                            <label class="block mb-1">Stock</label>
                            <input type="number" name="stock" value="{{ old('stock') }}"
                                class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                        </div>

                        <div>
                            <label class="block mb-1">Stock mínimo</label>
                            <input type="number" name="min_stock" value="{{ old('min_stock', 0) }}"
                                class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                        </div>

                        <div>
                            <label class="block mb-1">Estado</label>
                            <select name="status"
                                class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                                <option value="activo" @selected(old('status') == 'activo')>Activo</option>
                                <option value="descontinuado" @selected(old('status') == 'descontinuado')>Descontinuado</option>
                                <option value="reservado" @selected(old('status') == 'reservado')>Reservado</option>
                            </select>
                        </div>

                        <div>
                            <label class="block mb-1">Fecha de caducidad</label>
                            <input type="date" name="expiration_date" value="{{ old('expiration_date') }}"
                                class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block mb-1">Descripción</label>
                            <textarea name="description" rows="3" class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-gray-100">{{ old('description') }}</textarea>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block mb-1">Imagen</label>
                            <input type="file" name="image"
                                class="w-full px-4 py-2 border rounded dark:bg-gray-700 dark:text-gray-100">
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                            Guardar
                        </button>
                        <a href="{{ route('admin.products.index') }}"
                            class="px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600 transition">
                            Cancelar
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
