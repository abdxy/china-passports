<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $fillable = ['name', 'phone', 'country', 'city'];

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }
}
