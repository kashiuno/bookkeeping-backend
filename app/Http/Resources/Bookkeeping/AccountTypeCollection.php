<?php

namespace App\Http\Resources\Bookkeeping;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AccountTypeCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
        ];
    }
}
