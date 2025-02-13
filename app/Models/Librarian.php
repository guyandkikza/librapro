<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Librarian extends Authenticatable
{
    protected $table = 'librarians';  // ชื่อตารางที่ใช้เก็บข้อมูล Admin
    protected $fillable = ['username', 'password'];
    protected $hidden = ['password', 'remember_token'];
    public $timestamps = true;
}
