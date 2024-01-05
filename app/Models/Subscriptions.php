<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriptions extends Model
{
    use HasFactory; 
    protected $table = 'subscriptions';

    protected $fillable = [
        'title',
        'description',
        'price',
        'discount_price',
        'is_active',
        'style'
    ];
}
