<?php

namespace App\Models;

use App\Enums\IsActiveEnums;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Team extends Model
{
    use HasFactory;
    protected $table = 'teams';
    protected $fillable = [
        'id',
        'name',
        'image',
        'team_power',
        'created_at',
        'updated_at',
    ];

    /**
     * @return HasOne
     */
    public function team_power(): HasOne 
    {
        return $this->hasOne(TeamPower::class, 'team_id', 'id')
            ->where('status', IsActiveEnums::ACTIVE->value);
    }

    /**
     * @return HasOne
     */
    public function match(): HasOne 
    {
        return $this->hasOne(Matches::class, 'team_id', 'id');
    }

    /**
     * @return HasMany
     */
    public function matches(): HasMany 
    {
        return $this->hasMany(Matches::class, 'team_id', 'id');    
    }
}
