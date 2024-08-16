<?php
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;

Route::middleware(['CorsMiddleware'])->group(function () {
    Route::controller(TeamController::class)->group(function () {
        Route::get('all-teams', 'allTeams')->name('team.all-teams');
        Route::get('parse-league-teams', 'parseLeagueTeams')->name('team.parse-league-teams');
        Route::get('week-result', 'weekResult')->name('team.week-result');
        Route::get('prediction-of-week', 'predictionOfWeek')->name('team.prediction-of-week');
        Route::get('already-exist-data', 'alreadyExistData')->name('team.already-exist-data');
    });
});
