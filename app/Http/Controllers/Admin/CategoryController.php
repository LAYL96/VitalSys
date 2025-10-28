<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        // Obtenemos todas las categorías paginadas
        $categories = Category::orderBy('name', 'asc')->paginate(10);

        // Retornamos la vista con los datos
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string|max:1000',
        ]);

        // Crear categoría con asignación explícita, evitando problemas de $fillable
        $category = new Category();
        $category->name = trim($validated['name']);
        $category->description = isset($validated['description']) && $validated['description'] !== ''
            ? trim($validated['description'])
            : null;
        $category->save();

        // Redireccionar con mensaje de éxito
        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría creada correctamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        // Validación
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string|max:1000',
        ]);

        // Actualizar con asignación explícita
        $category->name = trim($validated['name']);
        $category->description = isset($validated['description']) && $validated['description'] !== ''
            ? trim($validated['description'])
            : null;
        $category->save();

        // Redireccionar con mensaje de éxito
        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría actualizada correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        // Antes de eliminar, se puede verificar si tiene productos asociados
        if (method_exists($category, 'products') && $category->products()->count() > 0) {
            return redirect()->route('admin.categories.index')
                ->with('error', 'No se puede eliminar la categoría porque tiene productos asociados.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Categoría eliminada correctamente.');
    }
}
