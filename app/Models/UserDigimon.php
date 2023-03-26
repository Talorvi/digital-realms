<?php

namespace App\Models;

use App\Models\Digimon\BaseDigimon;
use App\Models\Digimon\Digimon;
use App\Models\Food\Interface\FoodInterface;
use App\Models\Training\Interface\TrainingInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cache;

/**
 * Class UserDigimon
 *
 * @property int $id
 * @property int $user_id
 * @property int $digimon_id
 * @property string $name
 * @property int $exp
 * @property int $age
 * @property float $energy
 * @property float $hunger
 * @property float $weight
 * @property int $training
 * @property float $mess
 * @property bool $is_asleep
 * @property bool $is_dead
 * @property int $care_mistakes
 * @property int $battles
 * @property int $battles_won
 * @property int $overfeeds
 * @property int $consecutive_feedings
 * @property int $feeding_limit
 * @property Carbon|null $malnutrition_start
 * @property Carbon $sleeping_hour
 * @property Carbon $mess_start
 * @property Carbon|null $lights_off_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @method static where(string $string, $id)
 * @method static create(array $array)
 */
class UserDigimon extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'digimon_id',
        'name',
        'exp',
        'age',
        'energy',
        'hunger',
        'weight',
        'training',
        'mess',
        'is_asleep',
        'is_dead',
        'care_mistakes',
        'battles',
        'battles_won',
        'overfeeds',
        'consecutive_feedings',
        'feeding_limit',
        'malnutrition_start',
        'sleeping_hour',
        'mess_start'
    ];

    protected $casts = [
        'malnutrition_start' => 'datetime',
    ];

    public function getExp(): int
    {
        return $this->exp;
    }

    public function feed(FoodInterface $food): void
    {
        $this->consecutive_feedings++;

        if ($this->hunger <= 0) {
            if ($this->consecutive_feedings > $this->feeding_limit) {
                $this->addOverfeed();
            }
        } else {
            $this->hunger -= $food->getHungerRegeneration();
            $this->weight += $food->getWeightAddition();
            $this->energy += $food->getEnergyRegeneration();

            if ($this->hunger <= 0) {
                $this->hunger = 0;
                $this->consecutive_feedings = 0;
            }
        }
    }

    public function train(TrainingInterface $training)
    {
        $this->hunger += $training->getHungerReduction();
        $this->energy -= $training->getEnergyConsumption();
        $this->weight -= $training->getWeightReduction();
    }

    public function sleep(): void
    {
        $this->is_asleep = true;
    }

    public function wakeup(): void
    {
        $this->is_asleep = false;
    }

    public function clean(): void
    {
        $this->mess = 0;
    }

    public function addCareMistake(): void
    {
        $this->care_mistakes++;
    }

    public function getCareMistakes(): int
    {
        return $this->care_mistakes;
    }

    public function resetCareMistakes(): void
    {
        $this->care_mistakes = 0;
    }

    public function getWeight(): float
    {
        return $this->weight;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBattles(): int
    {
        return $this->battles;
    }

    public function addBattle(): void
    {
        $this->battles++;
    }

    public function getBattlesWon(): int
    {
        return $this->battles_won;
    }

    public function addBattleWon(): void
    {
        $this->battles_won++;
    }

    public function getOverfeeds(): int
    {
        return $this->overfeeds;
    }

    public function addOverfeed(): void
    {
        $this->overfeeds++;
    }

    public function getTraining(): int
    {
        return $this->training;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function resetOverfeed(): void
    {
        $this->overfeeds = 0;
    }

    public function addHunger(int $hunger): void
    {
        $this->hunger += $hunger;
        if ($this->hunger >= 100) {
            $this->hunger = 100;
            $this->malnutrition_start = Carbon::now();
        } else {
            $this->malnutrition_start = null;
        }
    }

    public function checkMalnutrition(): void
    {
        if ($this->malnutrition_start !== null) {
            $timeSinceMalnutritionStart = $this->malnutrition_start->diffInMinutes(Carbon::now());
            if ($timeSinceMalnutritionStart >= 60) {
                $this->addCareMistake();
                $this->malnutrition_start = Carbon::now();
            }
        }
    }

    public function isDead(): bool
    {
        return $this->is_dead;
    }

    public function calculateEvolutionSuccessRate(): int
    {
        $winRate = ($this->getBattlesWon() / $this->getBattles()) * 100;

        if ($winRate >= 100) {
            return 60;
        } elseif ($winRate >= 80) {
            return 50;
        } elseif ($winRate >= 70) {
            return 40;
        } elseif ($winRate >= 40) {
            return 20;
        }

        return 0;
    }

    public static function getDigimonClassMap(): array
    {
        return Cache::remember('digimon_class_map', 60 * 24, function () {
            $filesystem = new Filesystem();
            $stages = ['Fresh', 'InTraining', 'Rookie', 'Champion', 'Ultimate', 'Mega'];
            $digimonNameToClassMap = [];

            foreach ($stages as $stage) {
                $files = $filesystem->allFiles(app_path("Models/Digimon/{$stage}"));

                foreach ($files as $file) {
                    $className = 'App\\Models\\Digimon\\' . $stage . '\\' . pathinfo($file->getFilename(), PATHINFO_FILENAME);
                    $digimon = new $className();

                    $digimonNameToClassMap[$digimon->getName()] = $className;
                }
            }

            return $digimonNameToClassMap;
        });
    }

    public function getDigimonClass(): ?string
    {
        $digimonNameToClassMap = $this->getDigimonClassMap();

        return $digimonNameToClassMap[$this->digimon->name] ?? null;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function digimon(): BelongsTo
    {
        return $this->belongsTo(Digimon::class);
    }
}
