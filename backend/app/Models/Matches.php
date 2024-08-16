<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Matches extends Model
{
    use HasFactory;
    protected $table = 'matches';
    protected $guarded = [];

    /**
     * @return HasOne
     */
    public function team(): HasOne
    {
        return $this->hasOne(Team::class, 'id', 'team_id');
    }

    public function league(): HasOne
    {
        return $this->hasOne(League::class, 'id', 'league_id');
    }

    /**
     * @return HasOne
     */
    public function encounter(): HasOne
    {
        return $this->hasOne(Matches::class, 'id', 'league_id');
    }
}
