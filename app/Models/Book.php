<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    // Table name (optional, if not following Laravel's naming convention)
    protected $table = 'books';

    // Fillable attributes for mass assignment
    protected $fillable = [
        'title',
        'author',
        'code',
        'barcode',
        'publication_year',
        'description',
        'image'
    ];
}
