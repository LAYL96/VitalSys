<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ProductsExport;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function inventoryPdf()
    {
        $products = Product::with(['category', 'supplier'])->get();

        $pdf = Pdf::loadView('admin.reports.inventory', compact('products'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('reporte_inventario.pdf');
    }

    public function exportInventoryExcel()
    {
        return Excel::download(new ProductsExport, 'inventario.xlsx');
    }
}
