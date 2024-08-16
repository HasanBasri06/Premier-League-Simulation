<?php

namespace App\Jobs;

use App\Models\League;
use App\Models\Matches;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PlayFootball implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Collection $teams;
    protected final const LEAGUE_WEEKS_COUNT = 38;

    public function __construct(Collection $teams)
    {
        $this->teams = $teams;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->playLeagueMatch();
    }

    public function shuffleTeams(Collection $teams) {
        $shuffleTeams = $this->teams->shuffle();
        $firstTeam = $shuffleTeams->slice(0, 2);
        $secondTeam = $shuffleTeams->slice(2, 2);

        return [
            $firstTeam,
            $secondTeam
        ];
    }

    /**
     * @param Collection $teams
     * @return void
     */
    public function playLeagueMatch(): void
    {
        $premierLeagueStartDate = Carbon::create(Carbon::now()->year, 8, 1);
        for ($i=0; $i < self::LEAGUE_WEEKS_COUNT; $i++) {
            $shuffleTeam = $this->shuffleTeams($this->teams);
            $weekStartDate = $premierLeagueStartDate->startOfWeek();
            $getLeagueDetail = $this->getLeagueDetail($weekStartDate->format('Y-m-d H:i:s'));

            $this->match($shuffleTeam[0], $shuffleTeam[1], $getLeagueDetail->id);

            $premierLeagueStartDate->addWeek();
        }
    }

    /**
     * @param Collection $firstTeam
     * @param Collection $secondTeam
     * @param int $leagueId
     * @return void
     */
    public function match(Collection $firstTeam, Collection $secondTeam, int $leagueId): void
    {
        $firstGroupFirstTeamGoal = random_int(0, 5);
        $firstGroupSecondTeamGoal = random_int(0, 5);
        $secondGroupFirstTeamGoal = random_int(0, 5);
        $secondGroupSecondTeamGoal = random_int(0, 5);

        $groupOneNumber = random_int(0000, 9999);
        $groupTwoNumber = random_int(0000, 9999);

        $this->saveMatch($firstTeam[0]->id, $firstTeam[1]->id, $leagueId, $firstGroupFirstTeamGoal, $groupOneNumber);
        $this->saveMatch($firstTeam[1]->id, $firstTeam[0]->id, $leagueId, $firstGroupSecondTeamGoal, $groupOneNumber);
        $this->saveMatch($secondTeam[2]->id, $secondTeam[3]->id, $leagueId, $secondGroupFirstTeamGoal, $groupTwoNumber);
        $this->saveMatch($secondTeam[3]->id, $secondTeam[2]->id, $leagueId, $secondGroupSecondTeamGoal, $groupTwoNumber);
    }

    /**
     * @param int $teamId
     * @param int $parentId
     * @param int $leagueId
     * @param int $goal
     * @return void
     */
    protected function saveMatch(int $teamId, int $parentId, int $leagueId, int $goal, int $groupNumber): void
    {
        $Matches = new Matches;

        $Matches->team_id = $teamId;
        $Matches->parent_id = $parentId;
        $Matches->league_id = $leagueId;
        $Matches->goal = $goal;
        $Matches->group_number = $groupNumber;

        $Matches->save();
    }

    /**
     * @param string $startDate
     * @return League
     */
    public function getLeagueDetail(string $startDate): League
    {
        return League::where('start_date', $startDate)
            ->first();
    }
}
