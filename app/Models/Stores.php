<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stores extends Model
{
    use HasFactory;
    protected $table = 'stores';

    protected $fillable = [
        'user',
        'subdomain',
        'name',
        'logo',
        'theme',
        'location'
    ];
}
