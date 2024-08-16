<?php

namespace App\Jobs;

use App\Models\League;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateLeague implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Collection $teams;
    protected final const LEAGUE_WEEKS_COUNT = 38;

    /**
     * @param Collection $teams
     */
    public function __construct(Collection $teams)
    {
        $this->teams = $teams;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $generateWeeks = $this->generateLeagueWeeks();

        try {
            League::insert($generateWeeks->toArray());
        } catch (\Throwable $th) {
            throw new \Exception('There was an error creating league dates');
        }
    }

    /**
     * @return Collection
     */
    public function generateLeagueWeeks()
    {
        $weeks = Collection::make();
        $now = Carbon::now();
        $premierLeagueStartMonth = Carbon::create($now->year, 8, 1);

        for ($i=0; $i < self::LEAGUE_WEEKS_COUNT; $i++) {
            $weeks->push([
                'start_date' => $premierLeagueStartMonth->copy()->startOfWeek()->format('Y-m-d H:i:s'),
                'expire_date' => $premierLeagueStartMonth->copy()->endOfWeek()->format('Y-m-d H:i:s'),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);

            $premierLeagueStartMonth->addWeek();
        }

        return $weeks;
    }
}
