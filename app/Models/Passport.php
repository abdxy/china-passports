<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Passport extends Model
{
    protected $fillable = [
        'company_id',
        'sale_id',
        'full_name',
        'passport_id',
        'passport_image_url',
        'personal_image_url',
        'old_visa_url',
        'empty_page_passport_url',
        'have_china_previous_visa',
        'status',
        'price',
        'payment_status',
    ];

    protected $casts = [
        'have_china_previous_visa' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    protected static function booted()
    {
        static::creating(function ($passport) {
            if (!$passport->price) {
                $passport->price = $passport->have_china_previous_visa ? 900 : 1200;
            }
        });

        static::updating(function ($passport) {
            // Recalculate price if visa status changes and price wasn't manually overridden? 
            // Or just trust the default. Let's keep it simple: if changed, validation logic should handle it,
            // or we automagically update it here if necessary.
            // For now, let's only set default on create.
            if ($passport->isDirty('have_china_previous_visa')) {
                $passport->price = $passport->have_china_previous_visa ? 900 : 1200;
            }
        });
    }
}
