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
        // Productos con stock bajo
        $lowStockProducts = Product::whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock', 'asc')
            ->get();

        // Productos próximos a vencer en 30 días
        $expiringProducts = $this->getExpiringProducts(30);

        return view('admin.dashboard', compact('lowStockProducts', 'expiringProducts'));
    }

    /**
     * Obtener productos próximos a vencer (30 días antes).
     */
    private function getExpiringProducts($days = 30)
    {
        $today = now();
        $thresholdDate = $today->copy()->addDays($days);

        return Product::whereNotNull('expiration_date')
            ->whereDate('expiration_date', '<=', $thresholdDate)
            ->orderBy('expiration_date', 'asc')
            ->get();
    }
}
