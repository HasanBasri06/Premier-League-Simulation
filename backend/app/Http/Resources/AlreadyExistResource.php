<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AlreadyExistResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if (!$this['status']) {
            return [
                'status' => $this['status'],
                'data' => []
            ];
        }

        return [
            'status' => $this['status'],
            'data' => [
                'teams' => TeamResource::collection($this['teams']),

                'result' => new MatchResultResource($this['results']),

                'predictions' => PredictionResource::collection($this['predictions'])
            ]
        ];
    }
}
