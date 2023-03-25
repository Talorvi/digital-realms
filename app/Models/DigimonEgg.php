<?php

namespace App\Models;

use App\Models\Digimon\Digimon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $name
 * @property int $starter_digimon_id
 * @method static create(array $egg)
 * @method static firstOrCreate(array $array, array $array1)
 * @method static firstWhere(string $string, mixed $start_digimon_id)
 * @method static find(mixed $eggId)
 */
class DigimonEgg extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'starter_digimon_id',
    ];

    public function starterDigimon(): BelongsTo
    {
        return $this->belongsTo(Digimon::class, 'starter_digimon_id');
    }
}
