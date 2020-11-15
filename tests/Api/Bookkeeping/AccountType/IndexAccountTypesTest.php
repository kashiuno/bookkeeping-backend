<?php

namespace Tests\Api\Bookkeeping\AccountType;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Testing\TestResponse;
use Laravel\Passport\Passport;
use Tests\TestCase;

class IndexAccountTypesTest extends TestCase {

    use RefreshDatabase;

    private User $user;

    protected function setUp (): void {
        parent::setUp();

        Artisan::call('migrate');
        Artisan::call('passport:install');
        Artisan::call('db:seed', ['--class' => 'UserSeeder']);
        Artisan::call('db:seed', ['--class' => 'AccountTypeSeeder']);
        $this->user = User::where('email', 'taigusiobaka@gmail.com')
                          ->first()
        ;
    }

    public function testShouldReturn_200_successResponseOnSuccess () {
        Passport::actingAs($this->user);

        $response = $this->makeRequest();

        $response->assertOk();
    }

    public function testShouldReturn_401_responseIfUserNotAuthenticated () {
        $response = $this->makeRequest();

        $response->assertUnauthorized();
    }

    public function testShouldContainDataAndFieldsOnSuccess () {
        Passport::actingAs($this->user);

        $response = $this->makeRequest();

        $response->assertJsonStructure([
            'data' => [
                [
                    'id',
                    'name',
                    'user_id',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
    }

    public function testShouldContainEmptyDataIfUserDontHasAccountTypesOnSuccess () {
        Passport::actingAs(factory(User::class)->make());

        $response = $this->makeRequest();

        $response->assertExactJson([
            'data' => [],
        ]);
    }

    public function testShouldContainDataByCurrentUser() {
        Passport::actingAs($this->user);

        $response = $this->makeRequest();

        $response->assertJsonCount(10, 'data');
    }

    private function makeRequest(): TestResponse {
        return $this->json('GET', '/api/account_types');
    }
}