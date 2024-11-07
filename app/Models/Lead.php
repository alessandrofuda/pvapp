<?php

namespace App\Models;

use App\Enums\LeadStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lead extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Use casts to handle enum values automatically
    protected $casts = [
        'status' => LeadStatus::class
    ];

    public function area() : BelongsTo
    {
        return $this->belongsTo(Area::class);
    }
}
