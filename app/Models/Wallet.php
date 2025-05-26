<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'balance',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // public function hasSufficientBalance(float $amount): bool
    // {
    //     return $this->balance >= $amount;
    // }

    // public function debit(float $amount): bool
    // {
    //     if (!$this->hasSufficientBalance($amount)) {
    //         return false;
    //     }

    //     $this->wallet -= $amount;
    //     return $this->save();
    // }

    // public function credit(float $amount): bool
    // {
    //     $this->wallet += $amount;
    //     return $this->save();
    // }
}
