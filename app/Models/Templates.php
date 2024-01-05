<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Templates extends Model
{
    use HasFactory;
    protected $table = 'templates';

    protected $fillable = [
        'blade',
        'name',
        'description',
        'thumbnail_url',
        'css_styles',
        'javascript_code',
        'json_instructions',
        'is_active'
    ];
}
