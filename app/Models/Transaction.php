<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        "payer_id",
        "payee_id",
        "value",
        "status", // PENDING, AUTHORIZED, COMPLETED, FAILED, REVERSED
        "authorized_at",
        "completed_at",
    ];

    protected $casts = [
        "value" => "decimal:2",
        "authorized_at" => "datetime",
        "completed_at" => "datetime",
    ];

    /**
     * Get the user who sent the transaction (payer).
     */
    public function payer(): BelongsTo
    {
        return $this->belongsTo(User::class, "payer_id");
    }

    /**
     * Get the user who received the transaction (payee).
     */
    public function payee(): BelongsTo
    {
        return $this->belongsTo(User::class, "payee_id");
    }
}

