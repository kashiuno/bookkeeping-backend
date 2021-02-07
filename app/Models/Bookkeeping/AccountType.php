<?php

namespace App\Models\Bookkeeping;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class AccountType
 *
 * @package App\Models\Bookkeeping
 * @property
 */
class AccountType extends Model
{
    protected $table = 'account_types';

    protected $guarded = [];

    public function accounts(): HasMany {
        return $this->hasMany(Account::class);
    }
}
