<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function sentTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'payer_id');
    }

    public function receivedTransactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'payee_id');
    }

    public function isShopkeeper(): bool
    {
        return $this->user_type === 'shopkeeper';
    }

    public function canSendMoney(): bool
    {
        return $this->isComum();
    }

    public function createWalletIfNotExists()
    {
        if (!$this->wallet) {
            return $this->wallet()->create(['wallet' => 0.00]);
        }

        return $this->wallet;
    }
}
