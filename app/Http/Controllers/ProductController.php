<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Mostrar productos públicos.
     */
    public function publicIndex(Request $request)
    {

        $query = Product::query()->where('status', 'activo');

        // Buscar por nombre si viene el parámetro "search"
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->get();

        return view('products.public_index', compact('products'));

        // return "Hola desde publicIndex";
    }

    /**
     * Mostrar detalles de un producto específico.
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }
}
