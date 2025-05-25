<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'cpf_cnpj',
        'email',
        'password',
        'user_type',
        'wallet',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'wallet' => 'decimal:2',
    ];

    public function isComum()
    {
        return $this->user_type === 'COMUM';
    }

    public function isLojista(): bool
    {
        return $this->user_type === 'LOJISTA';
    }

    public function isAdmin(): bool
    {
        return strtoupper($this->user_type) === 'ADMIN';
    }
}
