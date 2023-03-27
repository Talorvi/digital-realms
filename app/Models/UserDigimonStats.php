<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $care_mistakes
 * @property int trainings
 * @property int battles
 * @property int $battles_won
 * @property int $age
 * @property int $deaths
 * @property int $feeds
 * @property int $illnesses
 * @method static create(array $array)
 */
class UserDigimonStats extends Model
{
    use HasFactory;

    protected $table = 'user_digimon_stats';

    protected $fillable = [
        'user_id',
        'care_mistakes',
        'trainings',
        'battles',
        'battles_won',
        'age',
        'deaths',
        'feeds',
        'illnesses'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
