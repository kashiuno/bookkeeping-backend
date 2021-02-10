<?php

namespace App;

use App\Models\Bookkeeping\Account;
use App\Models\Bookkeeping\AccountType;
use App\Models\Bookkeeping\Operation;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    public function accountTypes(): HasMany
    {
        return $this->hasMany(AccountType::class);
    }

    public function accounts(): HasMany {
        return $this->hasMany(Account::class);
    }

    public function operations(): HasMany {
        return $this->hasMany(Operation::class);
    }
}
