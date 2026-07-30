<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'order_id',
        'label',
        'amount',
        'due_date',
        'paid_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isPaid(): bool
    {
        return ! is_null($this->paid_at);
    }

    public function isOverdue(): bool
    {
        return ! $this->isPaid() && $this->due_date && $this->due_date->isPast();
    }

    public function statusLabel(): string
    {
        if ($this->isPaid()) {
            return 'Lunas';
        }

        return $this->isOverdue() ? 'Terlambat' : 'Menunggu';
    }

    public function statusTone(): string
    {
        if ($this->isPaid()) {
            return 'teal';
        }

        return $this->isOverdue() ? 'red' : 'amber';
    }

    public function amountFormatted(): string
    {
        return 'Rp '.number_format($this->amount, 0, ',', '.');
    }
}