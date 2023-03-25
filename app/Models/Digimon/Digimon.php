<?php

namespace App\Models\Digimon;

use App\Models\UserDigimon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'feeding_limit',
    ];

    public function userDigimons(): HasMany
    {
        return $this->hasMany(UserDigimon::class);
    }
}
