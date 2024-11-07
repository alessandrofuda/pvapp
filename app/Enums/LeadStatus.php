<?php

namespace App\Enums;

enum LeadStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Canceled = 'canceled';
}
