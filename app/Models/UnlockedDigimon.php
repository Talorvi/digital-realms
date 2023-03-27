<?php

namespace App\Models;

use App\Models\Digimon\Digimon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnlockedDigimon extends Model
{
    use HasFactory;

    protected $table = 'unlocked_digimon';

    protected $fillable = [
        'user_id',
        'digimon_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function digimon(): BelongsTo
    {
        return $this->belongsTo(Digimon::class);
    }
}
