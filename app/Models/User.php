<?php

namespace App\Models; // Pastikan namespace adalah App\Models

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'user'; // Nama tabel kustom
    protected $primaryKey = 'iduser'; // Primary key kustom
    public $timestamps = false; // Tidak ada created_at/updated_at

    protected $fillable = [
        'username', 'password', 'idrole',
    ];

    protected $hidden = [ 'password' ];

    // Memberitahu Laravel nama kolom password kustom
    public function getAuthPassword()
    {
        return $this->password;
    }
}