<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Prediction extends Model
{
    use HasFactory;
    protected $table = 'prediction_champions_of_league';
    protected $guarded = [];

    /**
     * @return HasMany
     */
    public function league(): HasMany
    {
        return $this->hasMany(League::class, 'id', 'league_id');
    }

    /**
     * @return HasOne
     */
    public function team(): HasOne
    {
        return $this->hasOne(Team::class, 'id', 'team_id');
    }
}
