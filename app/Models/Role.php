<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // Tambahkan ini

class Role extends Model
{
    protected $table = 'role'; 
    protected $primaryKey = 'idrole'; 
    public $timestamps = false; 
    protected $fillable = [
        'nama_role',
    ];

    // Tambahkan relasi ke User
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'idrole');
    }
}