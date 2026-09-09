<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;

    // Izinkan semua kolom diisi
    protected $guarded = [];

    // Relasi ke Product
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Relasi ke Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}