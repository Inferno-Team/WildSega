<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    const TYPE_CATEGORY = 'category';
    const TYPE_SEASON = 'season';
    const TYPE_FEATURE = 'feature';
    const TYPES = [
        self::TYPE_CATEGORY,
        self::TYPE_SEASON,
        self::TYPE_FEATURE
    ];

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
