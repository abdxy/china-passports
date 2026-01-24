<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $fillable = ['name', 'sale_id', 'country', 'city'];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function passports(): HasMany
    {
        return $this->hasMany(Passport::class);
    }
}
