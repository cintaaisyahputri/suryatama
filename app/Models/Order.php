<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $user_id
 * @property string|null $capacity
 * @property string|null $city
 * @property string|null $address
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $scheduled_at
 * @property string|null $technician_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Invoice> $invoices
 * @property-read int|null $invoices_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCapacity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereScheduledAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereTechnicianName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Order whereUserId($value)
 * @mixin \Eloquent
 */
class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'capacity',
        'city',
        'address',
        'status',
        'scheduled_at',
        'technician_name',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    protected $attributes = [
        'status' => 'menunggu_survei',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function invoices(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Invoice::class);
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

    public const HARGA_PER_KWP = 12000000;

    public function capacityKwp(): float
    {
        $numeric = preg_replace('/[^0-9,.]/', '', $this->capacity ?? '0');
        $numeric = str_replace(',', '.', $numeric);

        return (float) $numeric;
    }

    public function minimumPrice(): float
    {
        $kwp = $this->capacityKwp();

        return $kwp > 0 ? $kwp * self::HARGA_PER_KWP : self::HARGA_PER_KWP;
    }

    public function minimumPriceFormatted(): string
    {
        return 'Rp '.number_format($this->minimumPrice(), 0, ',', '.');
    }
}