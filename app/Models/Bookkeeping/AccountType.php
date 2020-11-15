<?php

namespace App\Models\Bookkeeping;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AccountType extends Model
{
    protected $table = 'account_types';

    protected $guarded = [];

    public function isMutableByCurrentUser(): bool
    {
        return $this->user_id === Auth::id();
    }
}
