<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(array $array)
 * @property int $id
 */
class Battle extends Model
{
    use HasFactory;

    protected $fillable = [
        'player1_digimon_id',
        'player2_digimon_id',
        'winner_digimon_id',
        'events',
    ];

    protected $casts = [
        'events' => 'array',
    ];
}
