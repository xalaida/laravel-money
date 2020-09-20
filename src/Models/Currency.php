<?php

declare(strict_types=1);

namespace Jeka\Money\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Currency extends Model
{
    use HasFactory;
}
