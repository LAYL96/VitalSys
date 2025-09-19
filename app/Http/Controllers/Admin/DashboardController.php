<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Mostrar dashboard con alertas de stock bajo.
     */
    public function index()
    {
        // Obtener productos con stock bajo
        $lowStockProducts = Product::whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock', 'asc')
            ->get();

        return view('admin.dashboard', compact('lowStockProducts'));
    }
}
