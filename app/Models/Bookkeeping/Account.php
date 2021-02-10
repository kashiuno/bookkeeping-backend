<?php

namespace App\Models\Bookkeeping;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function operations(): HasMany {
        return $this->hasMany(Operation::class);
    }
}
