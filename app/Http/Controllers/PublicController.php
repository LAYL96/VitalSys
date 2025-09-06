<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    // Página de inicio pública
    public function index()
    {
        $products = Product::all(); // O usa paginación si son muchos
        return view('welcome', compact('products'));
    }
}
