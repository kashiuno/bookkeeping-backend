<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class, 50)->make();
        User::create([
            'name' => 'kashiuno',
            'email' => 'taigusiobaka@gmail.com',
            'password' => Hash::make('asdasd'),
        ]);
    }
}
