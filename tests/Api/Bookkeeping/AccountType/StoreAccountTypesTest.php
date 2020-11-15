<?php

namespace Tests\Api\Bookkeeping\AccountType;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Testing\TestResponse;
use Laravel\Passport\Passport;
use Tests\TestCase;

class StoreAccountTypesTest extends TestCase {

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

    public function testShouldReturn_201OnSuccess () {
        Passport::actingAs($this->user);

        $requestData = [
            'name' => 'OverEngineering',
        ];

        $response = $this->makeRequest($requestData);

        $response->assertStatus(JsonResponse::HTTP_CREATED);
    }

    public function testShouldReturn_401IfUserNotAuthenticated () {
        $requestData = [
            'name' => 'OverEngineering',
        ];

        $response = $this->makeRequest($requestData);

        $response->assertUnauthorized();
    }

    public function testShouldReturnMessageAboutSuccessOnSuccess() {
        Passport::actingAs($this->user);

        $requestData = [
            'name' => 'OverEngineering',
        ];

        $response = $this->makeRequest($requestData);

        $response->assertExactJson([
            'message' => 'Тип счета успешно создан',
        ]);
    }

    public function testShouldReturnErrorMessageOnValidationFail () {
        Passport::actingAs($this->user);

        $requestData = [
            'name' => 'OverEngineeringasdasdasdasdasdasdawdadsfafwadasadwarasdaw',
        ];

        $response = $this->makeRequest($requestData);

        $response->assertJsonFragment([
            'message' => 'Данные неверные',
        ]);
    }

    public function testShouldReturnErrorsOnValidationFail () {
        Passport::actingAs($this->user);

        $requestData = [
            'name' => 'OverEngineeringasdasdasdasdasdasdawdadsfafwadasadwarasdaw',
        ];

        $response = $this->makeRequest($requestData);

        $response->assertJsonStructure([
            'errors' => [],
        ]);
    }

    public function testShouldCreateRecordInAccountTypesTableWithValidUserId () {
        Passport::actingAs($this->user);

        $requestData = [
            'name' => 'OverEngineering',
        ];

        $this->makeRequest($requestData);

        $this->assertDatabaseHas('account_types', ['name' => 'OverEngineering', 'user_id' => $this->user->id]);
    }

    private function makeRequest(array $requestData): TestResponse {
        return $this->json('POST', '/api/account_types', $requestData);
    }
}