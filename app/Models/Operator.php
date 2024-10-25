<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Operator extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function areas() : BelongsToMany
    {
        return $this->belongsToMany(Area::class, 'operator_areas', 'operator_id', 'region_id')
            // ->withPivot('province_id');  // include even province_id from pivot table
            ->withTimestamps();
    }
}
