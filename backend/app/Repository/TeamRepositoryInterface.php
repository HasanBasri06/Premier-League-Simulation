<?php

namespace App\Repository;

use App\Models\League;
use Illuminate\Database\Eloquent\Collection;

interface TeamRepositoryInterface {
    /**
     * @return Collection
     */
    public function getAllTeams(): Collection;

    /**
     * @return League
     */
    public function getLeagueDetail(string $startDate): League;

    /**
     * @param int $leagueId
     * @return Collection
     */
    public function getMatchDetailOfLeagueId(int $leagueId): Collection;

    /**
     * @param string $startDate
     * @return Collection
     */
    public function getPredictionOfWeek(string $startDate);

    /**
     * @param string $startedDate
     * @return Collection
     */
    public function getAlreadyExistResult(string $startedDate): Collection;

    /**
     * @param string $startedDate
     * @return Collection
     */
    public function getAlreadyExistPrediction(string $startedDate): Collection;
}
