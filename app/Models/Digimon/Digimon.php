<?php

namespace App\Models\Digimon;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static where(string $string, $getName)
 * @method static create(array $array)
 */
class Digimon extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'stage',
        'base_power',
        'type',
        'sleep_time',
    ];
}
