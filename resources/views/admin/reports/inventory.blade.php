<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Reporte de Inventario - VitalSys</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>Reporte de Inventario</h1>
    <table>
        <thead>
            <tr>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Proveedor</th>
                <th>Stock</th>
                <th>Precio (Q)</th>
                <th>Fecha de Vencimiento</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category?->name ?? 'Sin categoría' }}</td>
                    <td>{{ $product->supplier?->name ?? 'Sin proveedor' }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>Q {{ number_format($product->price, 2) }}</td>
                    <td>{{ $product->expiration_date ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
