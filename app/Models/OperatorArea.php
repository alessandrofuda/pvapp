<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OperatorArea extends Model
{
    use HasFactory;
    protected $table = 'operator_areas';

    public function region() : BelongsTo
    {
        return $this->belongsTo(Area::class, 'region_id', 'region_id');
    }
}
