<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Area extends Model
{
    use HasFactory;

//    public function region_operators() : BelongsToMany
//    {
//        return $this->belongsToMany(Operator::class, 'operator_areas', 'region_id', 'operator_id'); // to check
//    }
//
//    public function province_operators() : BelongsToMany
//    {
//        return $this->belongsToMany(Operator::class, 'operator_areas', 'province_id', 'operator_id'); // to check
//    }

    public function operators() : BelongsToMany
    {
        return $this->belongsToMany(Operator::class, 'operator_areas', 'region_id', 'operator_id');
            // ->withPivot('province_id');  // include even province_id from pivot table
    }


}
