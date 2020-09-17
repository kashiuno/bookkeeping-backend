<?php

namespace App\Http\Resources\Bookkeeping;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountTypeResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function toArray ($request) {
        return [
            'id'   => $this->id,
            'name' => $this->name,
        ];
    }
}
