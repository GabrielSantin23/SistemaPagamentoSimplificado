<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'payer_id',
        'payee_id',
        'amount',
        'status',
        'description',
        'authorization_code',
        'payer_notified',
        'payee_notified',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payer_notified' => 'boolean',
        'payee_notified' => 'boolean',
    ];

    public function payer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    public function payee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'payee_id');
    }

    public function markAsCompleted(?string $authorizationCode = null): bool
    {
        $this->status = 'completed';
        if ($authorizationCode) {
            $this->authorization_code = $authorizationCode;
        }
        return $this->save();
    }

    public function markAsFailed(): bool
    {
        $this->status = 'failed';
        return $this->save();
    }

    public function markAsReversed(): bool
    {
        $this->status = 'reversed';
        return $this->save();
    }

    public function markPayerNotified(): bool
    {
        $this->payer_notified = true;
        return $this->save();
    }

    public function markPayeeNotified(): bool
    {
        $this->payee_notified = true;
        return $this->save();
    }
}
