<?php

namespace App\Models\Bookkeeping;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Account extends Model
{
    protected $fillable = [
        'name',
        'account_type_id',
        'user_id',
        'sum'
    ];

    protected $table = 'accounts';

    public function accountType(): BelongsTo {
        return $this->belongsTo(AccountType::class);
    }
}
