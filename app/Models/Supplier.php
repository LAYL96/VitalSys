<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_info',
    ];

    // Relación: Un proveedor tiene muchos productos
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
