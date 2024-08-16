<?php

namespace App\Repository;

use App\Enums\IsActiveEnums;
use App\Models\League;
use App\Models\Matches;
use App\Models\Prediction;
use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;

class TeamRepository implements TeamRepositoryInterface {
    protected Team $team;
    protected League $league;
    protected Matches $matches;
    protected Prediction $prediction;

    /**
     * @param Team $team
     * @param League $league
     * @param Matches $matches
     * @param Prediction $prediction
     */
    public function __construct(Team $team, League $league, Matches $matches, Prediction $prediction) {
        $this->team = $team;
        $this->league = $league;
        $this->matches = $matches;
        $this->prediction = $prediction;
    }

    /**
     * @return Collection
     */
    public function getAllTeams(): Collection
    {
        return $this
            ->team
            ->with(['team_power'])
            ->get();
    }

    /**
     * @param string $startDate
     * @return League
     */
    public function getLeagueDetail(string $startDate): League
    {
        return $this->league
            ->where('start_date', $startDate)
            ->first();
    }

    /**
     * @param int $leagueId
     * @return Collection
     */
    public function getMatchDetailOfLeagueId(int $leagueId):Collection
    {
        $groupNumbers = [];
        $matchGroupNumbers = $this->matches
            ->where('league_id', $leagueId)
            ->select('group_number')
            ->groupBy('group_number')
            ->get()
            ->toArray();

        foreach ($matchGroupNumbers as $match) {
            array_push($groupNumbers, $match['group_number']);
        }

        return $this->matches
            ->whereIn('group_number', $groupNumbers)
            ->with(['team'])
            ->get();
    }

    /**
     * @param string $startDate
     * @return Collection
     */
    public function getPredictionOfWeek(string $startDate)
    {
        $getLeagueDetail = $this->getLeagueDetail($startDate);

        return $this
            ->prediction
            ->with([
                'league',
                'team'
            ])
            ->whereHas('league', function ($query) use ($getLeagueDetail) {
                return $query->where('id', $getLeagueDetail->id);
            })
            ->orderBy('win_rate', 'desc')
            ->get();
    }

    /**
     * @param string $startedDate
     * @return Collection
     */
    public function getAlreadyExistResult(string $startedDate): Collection
    {
        return $this->matches
            ->with([
                'team',
                'league' => function ($query) use ($startedDate) {
                    return $query->where('start_date', $startedDate);
                }
            ])
            ->get();

    }

    /**
     * @param string $startedDate
     * @return Collection
     */
    public function getAlreadyExistPrediction(string $startedDate): Collection
    {
        return $this->prediction
            ->with([
                'league',
                'team'
            ])
            ->whereHas('league', function ($query) use ($startedDate) {
                return $query->where('start_date', $startedDate);
            })
            ->get();
    }
}
