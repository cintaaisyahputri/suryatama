<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'label',
        'amount',
        'due_date',
        'paid_at',
        'status',
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (Invoice $invoice) {
            if (empty($invoice->status)) {
                $invoice->status = 'pending';
            }
        });
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid' || ! is_null($this->paid_at);
    }

    public function statusTone(): string
    {
        return match ($this->status) {
            'paid' => 'teal',
            'pending' => 'amber',
            'cancelled' => 'red',
            default => 'amber',
        };
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'paid' => 'Paid',
            'pending' => 'Pending',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->status),
        };
    }

    public function amountFormatted(): string
    {
        return 'Rp ' . number_format($this->amount, 0, ',', '.');
    }
}