<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;

    // Nama tabel sesuai di HeidiSQL
    protected $table = 'barang_masuks';

    // Tambahkan kolom yang boleh diisi secara massal
    protected $fillable = [
        'product_id',
        'supplier_id',
        'jumlah',
        'tanggal_masuk',
    ];

    // Relasi ke Model Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi ke Model Supplier
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}