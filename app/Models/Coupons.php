<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupons extends Model
{
    use HasFactory;
    protected $table = 'coupons';

    protected $fillable = [
        'user',
        'code',
        'started',
        'expiry',
        'is_active'
    ];
}
