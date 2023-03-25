<?php

namespace App\Models\Digimon;

use App\Enums\DigimonType;
use App\Models\UserDigimon;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $name
 * @property int $stage
 * @property int $base_power
 * @property DigimonType $type
 * @property Carbon $sleep_time
 * @property int $feeding_limit
 * @method static where(string $string, $getName)
 * @method static create(array $array)
 * @method static find(string $string, int $starter_digimon_id)
 * @method static first(string $string, int $starter_digimon_id)
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
