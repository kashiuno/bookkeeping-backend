<?php

namespace App\Http\Resources\Bookkeeping;

use Illuminate\Http\Resources\Json\ResourceCollection;

class AccountTypeCollection extends ResourceCollection
{
    public static $wrap = '';
    public function toArray($request)
    {
        return $this->collection;
    }
}
