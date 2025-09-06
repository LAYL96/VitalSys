<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Campos que se pueden asignar en masa
    protected $fillable = [
        'name',
        'sku',
        'category_id',
        'supplier_id',
        'description',
        'image',
        'price',
        'stock',
        'min_stock',
        'expiration_date',
        'status',
    ];

    // Esto asegura que expiration_date siempre sea un objeto Carbon
    protected $casts = [
        'expiration_date' => 'datetime',
    ];

    // Relación con Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relación con Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
