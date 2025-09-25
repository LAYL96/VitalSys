<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function inventoryPdf()
    {
        $products = Product::with(['category', 'supplier'])->get();

        $pdf = Pdf::loadView('admin.reports.inventory', compact('products'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('reporte_inventario.pdf');
    }
}
