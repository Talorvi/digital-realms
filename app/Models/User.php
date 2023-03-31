<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property UserDigimonStats $userDigimonStats
 * @property mixed $deviceTokens
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function unlockDigimon(int $digimonId)
    {
        $isUnlocked = $this->unlockedDigimon()->where('digimon_id', $digimonId)->exists();

        if (!$isUnlocked) {
            $this->unlockedDigimon()->create([
                'digimon_id' => $digimonId,
            ]);
        }
    }

    public function addCareMistakeStat(): void
    {
        $this->userDigimonStats->care_mistakes++;
        $this->userDigimonStats->save();
    }

    public function addTrainingsStat(): void
    {
        $this->userDigimonStats->trainings++;
        $this->userDigimonStats->save();
    }

    public function addBattlesStat(): void
    {
        $this->userDigimonStats->battles++;
        $this->userDigimonStats->save();
    }

    public function addBattlesWonStat(): void
    {
        $this->userDigimonStats->battles_won++;
        $this->userDigimonStats->save();
    }

    public function incrementAgeStat(): void
    {
        $this->userDigimonStats->age++;
        $this->userDigimonStats->save();
    }

    public function incrementDeathsStat(): void
    {
        $this->userDigimonStats->deaths++;
        $this->userDigimonStats->save();
    }

    public function incrementFeeds(): void
    {
        $this->userDigimonStats->feeds++;
        $this->userDigimonStats->save();
    }

    public function incrementIllnesses(): void
    {
        $this->userDigimonStats->illnesses++;
        $this->userDigimonStats->save();
    }

    public function digimons(): HasMany
    {
        return $this->hasMany(UserDigimon::class);
    }

    public function unlockedDigimon(): HasMany
    {
        return $this->hasMany(UnlockedDigimon::class);
    }

    public function userDigimonStats(): HasOne
    {
        return $this->hasOne(UserDigimonStats::class);
    }

    public function deviceTokens(): HasMany
    {
        return $this->hasMany(DeviceToken::class);
    }

    public function routeNotificationForFcm()
    {
        return $this->deviceTokens->pluck('token')->toArray();
    }
}
