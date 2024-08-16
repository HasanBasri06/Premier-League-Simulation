<?php

namespace App\Jobs;

use App\Models\Matches;
use App\Models\Prediction;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Ramsey\Collection\Collection;

class PredictionMatchRate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $getAllMatchByGroupName = $this->getAllMatchByGroupName();

        foreach ($getAllMatchByGroupName as $leagueMatch) {
            $predictions = $this->calculateWeeklyWinningProbability($leagueMatch);

            Prediction::insert($predictions->toArray());
        }


    }

    /**
     * @return mixed
     */
    public function getAllMatchByGroupName(): mixed {
        return Matches::with('encounter', 'league')
            ->get()
            ->groupBy('league_id')
            ->map(function ($query) {
                return $query->toArray();
            })
            ->values()->all();
    }

    /**
     * @param array $groupMatchs
     * @return \Illuminate\Support\Collection
     */
    public function calculateWeeklyWinningProbability(array $groupMatchs): \Illuminate\Support\Collection
    {
        $teamWithPoints = collect([]);
        $winMatchPoint = 3;

        foreach ($groupMatchs as $match) {
            $point = 0;

            if ($match['goal'] > $match['encounter']['goal']) {
                $point += $winMatchPoint + $match['goal'] - $match['encounter']['goal'];
            }

            if ($match['goal'] < $match['encounter']['goal']) {
                $point += $match['goal'] - $match['encounter']['goal'];
            }

            $teamWithPoints->push([
                'team_id' => $match['team_id'],
                'league_id' => $match['league']['id'],
                'point' => $point
            ]);
        }

        $teamWithPoints = $teamWithPoints->sortByDesc('point')->values();

        $percentages = collect([
            [
                'team_id' => $teamWithPoints[0]['team_id'],
                'win_rate' => 60,
                'league_id' => $teamWithPoints[0]['league_id'],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'team_id' => $teamWithPoints[1]['team_id'],
                'win_rate' => 20,
                'league_id' => $teamWithPoints[1]['league_id'],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'team_id' => $teamWithPoints[2]['team_id'],
                'win_rate' => 15,
                'league_id' => $teamWithPoints[2]['league_id'],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'team_id' => $teamWithPoints[3]['team_id'],
                'win_rate' => 5,
                'league_id' => $teamWithPoints[3]['league_id'],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
        ]);

        return $percentages;
    }
}
