<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'iduser';
    public $timestamps = false; // Sesuai schema SQL Anda

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'username',
        'password', // Hati-hati, ini plain text!
        'idrole',
    ];

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     */
    protected function casts(): array
    {
        return []; // Kosongkan karena password tidak di-hash
    }

    // Relasi ke Role
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'idrole');
    }

    // Method helper untuk cek role
    public function hasRole(string $roleName): bool
    {
        return $this->role && $this->role->nama_role === $roleName;
    }

    // --- Penyesuaian untuk Laravel Auth ---

    /**
     * Gunakan 'username' sebagai pengganti 'email' untuk login.
     */
    public function getAuthIdentifierName()
    {
        return 'username';
    }

    /**
     * Ambil password (plain text dalam kasus ini).
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

     /**
      * Ambil identifier unik (primary key).
      */
     public function getAuthIdentifier()
     {
         return $this->getKey();
     }

     /**
      * Kosongkan method terkait "remember token" jika tidak ada kolomnya.
      */
     public function getRememberToken()
     {
         return null;
     }

     public function setRememberToken($value)
     {
         // Tidak melakukan apa-apa
     }

     public function getRememberTokenName()
     {
        return null;
     }
}