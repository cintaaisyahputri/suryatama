<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'capacity',
        'city',
        'address',
        'status',
    ];

    protected $attributes = [
        'status' => 'menunggu_survei',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            'menunggu_survei' => 'Menunggu survei',
            'survei_terjadwal' => 'Survei terjadwal',
            'pemasangan' => 'Pemasangan',
            'aktif' => 'Aktif',
            default => ucfirst(str_replace('_', ' ', $this->status)),
        };
    }

    public function statusTone(): string
    {
        return match ($this->status) {
            'menunggu_survei' => 'amber',
            'survei_terjadwal' => 'teal',
            default => 'ink',
        };
    }
}