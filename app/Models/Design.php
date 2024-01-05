<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Design extends Model
{
    use HasFactory;
    protected $table = 'design';

    protected $fillable = [
        'store',
        'template',
        'name',
        'css_styles',
        'javascript_code',
        'json_file',
        'is_active',
        'is_verify'
    ];
}
