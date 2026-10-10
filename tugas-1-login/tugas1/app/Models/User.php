<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang dapat diisi secara mass-assignment.
     */
    protected $fillable = [
        'username',
        'nama_lengkap',
        'password',
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Tipe casting atribut.
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
