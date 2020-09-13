<?php

namespace Tests\Api\Auth;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
        Artisan::call('passport:install');
        Artisan::call('db:seed', ['--class' => 'UserSeeder']);
        $this->user = User::where('email', 'taigusiobaka@gmail.com')->first();
    }

    public function testUserCanLogoutIfAuthenticated()
    {
        Passport::actingAs($this->user);

        $this
            ->withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
            ->post('/api/logout')
            ->assertStatus(JsonResponse::HTTP_OK)
            ->assertExactJson([
                'message' => 'You are successfully logged out',
            ]);
    }

    public function testUserCannotLogoutIfNotAuthenticated()
    {
        $this->withHeaders(['Content-Type' => 'application/json', 'Accept' => 'application/json'])
            ->post('/api/logout')
            ->assertStatus(JsonResponse::HTTP_UNAUTHORIZED)
            ->assertExactJson([
                'message' => 'Unauthenticated.',
            ]);
    }
}
