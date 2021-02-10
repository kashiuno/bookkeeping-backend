<?php

namespace App\Models\Bookkeeping;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $fillable = [
        'account_id',
        'user_id',
        'amount',
        'incoming',
        'description'
    ];

    public function account() {
        $this->belongsTo(Account::class);
    }
}