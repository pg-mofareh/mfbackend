<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreSubs extends Model
{
    use HasFactory;
    protected $table = 'st_subs';

    protected $fillable = [
        'store',
        'subscription',
        'payment_method',
        'price',
        'discount_price',
        'tax',
        'discount',
        'total',
        'start_at',
        'end_at'
    ];
}
