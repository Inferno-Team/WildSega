<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlantTag extends Model
{
    use HasFactory;

    protected $fillable = [
        'plant_id',
        'tag_id',
    ];
    public function plant(): BelongsTo
    {
        return $this->belongsTo(Plant::class);
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }
}
