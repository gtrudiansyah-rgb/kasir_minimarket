<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // Mengizinkan seluruh kolom diisi secara fleksibel
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke detail transaksi (menyelesaikan Error 500)
    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}