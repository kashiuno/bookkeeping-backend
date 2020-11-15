<?php

use App\Models\Bookkeeping\AccountType;
use App\User;
use Illuminate\Database\Seeder;

class AccountTypeSeeder extends Seeder
{
    public function run(): void
    {
        factory(AccountType::class, 50)->create();
        factory(AccountType::class, 10)->create(
            [
                'user_id' => User::where('email', 'taigusiobaka@gmail.com')
                                 ->first()->id,
            ]
        );
    }
}
