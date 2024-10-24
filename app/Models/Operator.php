<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Operator extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function regions() : BelongsToMany
    {
        return $this->belongsToMany(Area::class, 'operator_areas', 'operator_id', 'region_id')->withTimestamps();
    }

    public function provinces() : BelongsToMany
    {
        return $this->belongsToMany(Area::class, 'operator_areas', 'operator_id', 'province_id')->withTimestamps();
    }
}
