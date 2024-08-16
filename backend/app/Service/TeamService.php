<?php

namespace App\Service;

use App\Enums\IsActiveEnums;
use App\Enums\LeagueEnum;
use App\Jobs\CreateLeague;
use App\Jobs\GenerateTeamPower;
use App\Jobs\PlayFootball;
use App\Jobs\PredictionMatchRate;
use App\Repository\TeamRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Bus;

class TeamService {
    protected TeamRepositoryInterface $teamRepository;
    protected final const LEAGUE_WEEKS_COUNT = 38;

    /**
     * @param TeamRepositoryInterface $teamRepository
     */
    public function __construct(TeamRepositoryInterface $teamRepository) {
        $this->teamRepository = $teamRepository;
    }

    /**
     * @return Collection
     */
    public function getAllTeams(): Collection
    {
       return $this->teamRepository->getAllTeams();
    }

    /**
     * @param Collection $teams
     * @return void
     */
    public function parseLeagueTeams(Collection $teams) {

        Bus::chain([
            new CreateLeague(teams: $teams),
            new PlayFootball(teams: $teams),
            new GenerateTeamPower(),
            new PredictionMatchRate(),
        ])
        ->dispatch();
    }

    /**
     * @param string $startDate
     * @return Collection
     */
    public function getleagueMatchDetailByStartDate(string $startDate): Collection
    {
        $startDate = Carbon::parse($startDate)->startOfWeek();
        $getLeagueDetail = $this->teamRepository->getLeagueDetail($startDate);

        return $this->teamRepository->getMatchDetailOfLeagueId($getLeagueDetail->id);
    }

    /**
     * @param string $startDate
     * @return Collection
     */
    public function getPredictionOfWeek(string $startDate): Collection
    {
        $startDate = Carbon::parse($startDate)->startOfWeek();
        return $this->teamRepository->getPredictionOfWeek($startDate);
    }

    /**
     * @return Collection
     */
    public function getAlreadyExistData() {
        $leagueStartedWeek = Carbon::parse(LeagueEnum::STARTED_DATE_STRING->value)->startOfWeek();

        $getAllTeams = $this->teamRepository->getAllTeams();
        $results = $this->teamRepository->getAlreadyExistResult($leagueStartedWeek);
        $predictions = $this->teamRepository->getAlreadyExistPrediction($leagueStartedWeek);
        $setStatus = $results->isNotEmpty() ? IsActiveEnums::ACTIVE->value : IsActiveEnums::DEACTIVE->value;

        $alreadyExistData = collect([
            'status' => $setStatus,
            'teams' => $getAllTeams,
            'results' => $results,
            'predictions' => $predictions
        ]);

        return $alreadyExistData;
    }
}
