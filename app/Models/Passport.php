<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

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

    protected $appends = [
        'passport_image_signed_url',
        'personal_image_signed_url',
        'old_visa_signed_url',
        'empty_page_passport_signed_url',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Generate presigned URL for an image field
     */
    protected function getSignedUrl(?string $url): ?string
    {
        if (!$url) {
            return null;
        }

        // Extract path from full URL (e.g., https://china.fra1.digitaloceanspaces.com/uploads/file.png -> uploads/file.png)
        $baseUrl = config('filesystems.disks.do.url');
        $path = str_replace(rtrim($baseUrl, '/') . '/', '', $url);

        // If path is the same as URL, it might be stored differently
        if ($path === $url) {
            // Try to extract just the path portion
            $parsed = parse_url($url);
            $path = ltrim($parsed['path'] ?? '', '/');
        }

        if (empty($path)) {
            return null;
        }

        try {
            // Generate presigned URL valid for 60 minutes
            return Storage::disk('do')->temporaryUrl($path, now()->addMinutes(60));
        } catch (\Exception $e) {
            // If presigned URL fails, return original URL
            return $url;
        }
    }

    public function getPassportImageSignedUrlAttribute(): ?string
    {
        return $this->getSignedUrl($this->passport_image_url);
    }

    public function getPersonalImageSignedUrlAttribute(): ?string
    {
        return $this->getSignedUrl($this->personal_image_url);
    }

    public function getOldVisaSignedUrlAttribute(): ?string
    {
        return $this->getSignedUrl($this->old_visa_url);
    }

    public function getEmptyPagePassportSignedUrlAttribute(): ?string
    {
        return $this->getSignedUrl($this->empty_page_passport_url);
    }

    protected static function booted()
    {
        static::creating(function ($passport) {
            if (!$passport->price) {
                $passport->price = $passport->have_china_previous_visa ? 900 : 1200;
            }
        });

        static::updating(function ($passport) {
            if ($passport->isDirty('have_china_previous_visa')) {
                $passport->price = $passport->have_china_previous_visa ? 900 : 1200;
            }
        });
    }
}
