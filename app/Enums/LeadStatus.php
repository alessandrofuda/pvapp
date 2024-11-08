<?php

namespace App\Enums;

enum LeadStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Canceled = 'canceled';

    // Optional method to get all values as an array for convenience
    public static function options_for_select(): array
    {
        return array_map(function($status) {
            return ['id' => $status->value, 'name' => $status->name];
        }, self::cases());
    }
}
