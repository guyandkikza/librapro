<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Borrow extends Model
{
    use HasFactory;
    
    protected $table = 'borrowings';
    protected $primaryKey = 'borrowing_id';

    protected $fillable = [
        'user_id',
        'barcode',
        'borrowed_at',
        'returned_at',
        'status'
    ];
}
