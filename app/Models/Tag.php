<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
    ];

    public function userPreferences(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(UserPreference::class);
    }

    public function plantTags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Plant::class, PlantTag::class);
    }
}
