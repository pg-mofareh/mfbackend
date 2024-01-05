<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilesPublic extends Model
{
    use HasFactory;
    protected $table = 'files_public';
    protected $fillable = [
        'title',
        'name',
        'extension',
        'directories'
    ];
}
