<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property int $hunger_regeneration
 * @property int $weight_addition
 * @property int $energy_regeneration
 * @method static where(string $string, $getName)
 * @method static create(array $array)
 */
class Food extends Model
{
    use HasFactory;

    protected $table = 'foods';

    protected $fillable = [
        'name',
        'hunger_regeneration',
        'weight_addition',
        'energy_regeneration'
    ];
}
