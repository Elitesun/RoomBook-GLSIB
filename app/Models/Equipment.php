<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Equipment extends Model
{
    /** @use HasFactory<\Database\Factories\EquipmentFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'quantity',
        'is_available',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'is_available' => 'boolean',
        ];
    }

    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class)->withPivot('quantity')->withTimestamps();
    }
}
