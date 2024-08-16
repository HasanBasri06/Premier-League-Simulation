<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'image' => $this->image,
            'team_power' => [
                'PTS' => $this->team_power->PTS ?? null,
                'P' => $this->team_power->P ?? null,
                'W' => $this->team_power->W ?? null,
                'D' => $this->team_power->D ?? null,
                'L' => $this->team_power->L ?? null,
                'GD' => $this->team_power->GD ?? null
            ],
            'created_at' => Carbon::parse($this->created_at)->format('d.m.Y H:i')
        ];
    }
}
