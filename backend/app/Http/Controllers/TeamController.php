<?php

namespace App\Http\Controllers;

use App\Http\Resources\AlreadyExistResource;
use App\Http\Resources\MatchResultResource;
use App\Http\Resources\PredictionResource;
use App\Http\Resources\StartedTeamTableResource;
use App\Http\Resources\TeamResource;
use App\Service\TeamService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as QA;

class TeamController extends Controller
{
    protected TeamService $teamService;

    /**
     * @param TeamService $teamService
     */
    public function __construct(TeamService $teamService) {
        $this->teamService = $teamService;
    }

    /**
     * @return AnonymousResourceCollection
     */
    #[QA\Get(path: '/api/all-teams', tags: ['Team'])]
    #[QA\Response(response: 200, description: 'lists all teams in the league')]
    public function allTeams(): AnonymousResourceCollection
    {
        $allTeams = $this->teamService->getAllTeams();

        return TeamResource::collection($allTeams);
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    #[QA\Get(path: '/api/parse-league-teams', tags: ['Team'])]
    #[QA\Response(response: 200, description: 'matches teams against other teams throughout the league.')]
    public function parseLeagueTeams(Request $request): AnonymousResourceCollection
    {
        $getAllTeams = $this->teamService->getAllTeams();
        $this->teamService->parseLeagueTeams($getAllTeams);
        $getAllTeams = $this->teamService->getAllTeams();

        return TeamResource::collection($getAllTeams);
    }

    /**
     * @param Request $request
     * @return MatchResultResource
     */
    #[QA\Get(path: '/api/week-result', tags: ['Team'], parameters: [
        new QA\Parameter(
            name: 'startDate',
            in: 'query',
            description: 'It returns you the value of that week based on the value you send.',
        )
    ])]
    #[QA\Response(response: '200', description: 'If you do not send a parameter, it gives the start date of the league. If you give a parameter, it gives you the value of that week based on the parameter you give.')]
    public function weekResult(Request $request): MatchResultResource
    {
        $leagueStartDate = $this->hasStartDate($request);
        $getLeagueMatchDetail = $this->teamService->getleagueMatchDetailByStartDate($leagueStartDate);

        return new MatchResultResource($getLeagueMatchDetail);
    }

    /**
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    #[QA\GET(
        path: '/api/prediction-of-week',
        tags: ['Team'],
        description: 'It returns you the value of that week based on the value you sent.',
        parameters: [
            new QA\Parameter(
                name: 'startDate',
                in: 'query',
            )
        ],
        responses: [
            new QA\Response(
                response: 200,
                description: ''
            )
        ]
    )]
    public function predictionOfWeek(Request $request): AnonymousResourceCollection
    {
        $leagueStartDate = $this->hasStartDate($request);
        $predictionsOfWeek = $this->teamService->getPredictionOfWeek($leagueStartDate);

        return PredictionResource::collection($predictionsOfWeek);
    }

    /**
     * @return AlreadyExistResource
     */
    #[QA\Get(
        path: 'api/already-exist-data',
        description: 'If the teams have been created, I wrote an endpoint for the frontend that will show the old data when the page is refreshed.',
        tags: ['Team'],
        responses: [
            new QA\Response(
                response: 200,
                description: ''
            )
        ]
    )]
    public function alreadyExistData() {
        $getAlreadyExistData = $this->teamService->getAlreadyExistData();

        return new AlreadyExistResource($getAlreadyExistData);
    }

    /**
     * @param Request $request
     * @return string
     */
    protected function hasStartDate(Request $request): string
    {
        return $request->has('startDate') ? $request->get('startDate') : Carbon::create(Carbon::now()->year, 8, 1)->format('Y-m-d H:i:s');
    }
}
