<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'total_price',
        'pay_amount',
        'return_amount',
    ];

    public function details()
    {
        return $this->hasMany(TransactionDetail::class);
    }
}