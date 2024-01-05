<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfers extends Model
{
    use HasFactory;
    protected $table = 'transfers';

    protected $fillable = [
        'store',
        'subscription',
        'file',
        'total',
        'status',
        'note'
    ];
}
