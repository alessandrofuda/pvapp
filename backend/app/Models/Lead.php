<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;

    protected $fillable = [
        'form',
        'services_ids',
        'name',
        'surname',
        'email',
        'phone',
        'municipality',
        'province',
        'region',
        'price',
        'description',
        'phone_verified',
        'approved',
        'sales_counter',
        'notes',
        'fake_lead'
    ];

    public const SERVICE = [
        'pv_plant' => 1,
        'storage_batteries' => 2,
        'heat_pump' => 3,
        'energy_efficiency' => 4,
        'domotic' => 5,
        'maintenance_repair' => 6
    ];

    public const PRICE = [
        'default' => 12.00,
    ];
}
