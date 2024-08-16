<?php

namespace App\Jobs;

use App\Enums\IsActiveEnums;
use App\Models\Matches;
use App\Models\Team;
use App\Models\TeamPower;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

use function PHPUnit\Framework\matches;

class GenerateTeamPower implements ShouldQueue
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
    public function handle(Team $team, Matches $matches): void
    {
        $allMatchGroup = $matches
            ->with(['encounter'])
            ->get()
            ->groupBy('team_id');
        
        foreach ($allMatchGroup as $team) {
            $lostMatchCount = 0;
            $wonMatchCount = 0;
            $sameMatchCount = 0;
            $goalDifference = 0;
            $teamPoints = 0;
            
            foreach ($team as $match) {
                if ($match->goal < $match->encounter->goal) {
                    $lostMatchCount += 1;
                }

                if ($match->goal > $match->encounter->goal) {
                    $wonMatchCount += 1;
                    $teamPoints += 3;
                }

                if ($match->goal == $match->encounter->goal) {
                    $sameMatchCount += 1;
                    $teamPoints += 1;
                }
            }

            $teamOneGoal = $team->sum('goal');
            $teamSecondGoal = $team->sum('encounter.goal');

            if ($teamOneGoal < $teamSecondGoal) {
                $goalDifference = '-'.$team->sum('encounter.goal') - $team->sum('goal');
            } 
            
            if ($teamOneGoal > $teamSecondGoal) {
                $goalDifference = '+'.$team->sum('goal') - $team->sum('encounter.goal');
            }

            TeamPower::where('team_id', $team->first()->id)
                ->update(['status' => IsActiveEnums::DEACTIVE->value]);

            $TeamPower = new TeamPower();
            $TeamPower->PTS = $teamPoints;
            $TeamPower->L = $wonMatchCount;
            $TeamPower->W = $lostMatchCount;
            $TeamPower->D = $sameMatchCount;
            $TeamPower->P = $team->count();
            $TeamPower->GD = $goalDifference;
            $TeamPower->team_id = $team->first()->id;
            $TeamPower->status = IsActiveEnums::ACTIVE->value;

            $TeamPower->save();
        }       
    }
}
