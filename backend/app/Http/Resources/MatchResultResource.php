<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MatchResultResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'group_one' => [
                'team_one' => [
                    'name' => $this[0]->team->name,
                    'goal' => $this[0]->goal,
                    'image' => $this[0]->team->image
                ],
                'team_two' => [
                    'name' => $this[1]->team->name,
                    'goal' => $this[1]->goal,
                    'image' => $this[1]->team->image
                ],
            ],

            'group_two' => [
                'team_one' => [
                    'name' => $this[2]->team->name,
                    'goal' => $this[2]->goal,
                    'image' => $this[2]->team->image
                ],
                'team_two' => [
                    'name' => $this[3]->team->name,
                    'goal' => $this[3]->goal,
                    'image' => $this[3]->team->image
                ],
            ],
        ];
    }
}
