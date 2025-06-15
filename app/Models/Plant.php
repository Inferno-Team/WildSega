<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Plant extends Model implements HasMedia
{
    /** @use HasFactory<\Database\Factories\PlantFactory> */
    use HasFactory;
    use InteractsWithMedia;

    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    const statusTypes = [
        self::STATUS_PENDING,
        self::STATUS_APPROVED,
        self::STATUS_REJECTED,
    ];

    protected $fillable = [
        'common_name',
        'scientific_name',
        'description',
        'safety_notes',
        'harvesting_tips',
    ];


    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,
            PlantTag::class
        )->withTimestamps();
    }

    public function discoveries()
    {
        return $this->hasMany(PlantDiscovery::class);
    }
}
