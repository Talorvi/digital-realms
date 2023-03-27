<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $name
 * @property int $hunger_reduction
 * @property int $weight_reduction
 * @property int $energy_consumption
 * @method static where(string $string, $getName)
 * @method static create(array $array)
 */
class Training extends Model
{
    use HasFactory;

    protected $table = 'trainings';

    protected $fillable = [
        'name',
        'hunger_reduction',
        'weight_reduction',
        'energy_consumption'
    ];
}
