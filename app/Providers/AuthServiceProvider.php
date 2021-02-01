<?php

namespace App\Providers;

use App\Models\Bookkeeping\AccountType;
use App\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        $this->definedAccountTypesGates();
    }

    private function definedAccountTypesGates(): void {
        Gate::define('update-account-type', function (User $user, AccountType $accountType) {
            return $user->id == $accountType->user_id;
        });
    }
}
