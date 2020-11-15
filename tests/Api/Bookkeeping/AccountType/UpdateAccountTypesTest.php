<?php

namespace Tests\Api\Bookkeeping\AccountType;

use App\Models\Bookkeeping\AccountType;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Testing\TestResponse;
use Laravel\Passport\Passport;
use Tests\TestCase;

class UpdateAccountTypesTest extends TestCase {

    use RefreshDatabase;
    private User $testUser;

    private AccountType $testAccountType;
    private AccountType $testAccountTypeAnother;

    protected function setUp (): void {
        parent::setUp();

        Artisan::call('migrate');
        Artisan::call('passport:install');
        Artisan::call('db:seed', ['--class' => 'UserSeeder']);
        Artisan::call('db:seed', ['--class' => 'AccountTypeSeeder']);
        $this->testUser = User::where('email', 'taigusiobaka@gmail.com')
                          ->first()
        ;
        $this->testAccountType = AccountType::where('user_id', $this->testUser->id)->first();
        $this->testAccountTypeAnother = AccountType::where('user_id', '!=', $this->testUser->id)->first();
    }

    public function testShouldReturn_200ResponseOnSuccess() {
        Passport::actingAs($this->testUser);

        $requestData = [
            'name' => 'OverEngineering',
        ];

        $response = $this->makeRequest($requestData);

        $response->assertOk();
    }

    public function testShouldReturnSuccessMessageOnSuccess() {
        Passport::actingAs($this->testUser);

        $requestData = [
            'name' => 'OverEngineering',
        ];

        $response = $this->makeRequest($requestData);

        $response->assertExactJson(['message' => 'Изменение типа счета произошло успешно']);
    }

    public function testShouldReturnErrorMessageOnFail() {
        Passport::actingAs($this->testUser);

        $requestData = [
            'name' => 'Супер счета моей мега крутой бабушки из деревни с описанием состояния ее головы',
        ];

        $response = $this->makeRequest($requestData);

        $response->assertJsonFragment(['message' => 'Данные неверные']);
    }

    public function testShouldReturnErrorsFieldOnFail() {
        Passport::actingAs($this->testUser);

        $requestData = [
            'name' => 'Супер счета моей мега крутой бабушки из деревни с описанием состояния ее головы',
        ];

        $response = $this->makeRequest($requestData);

        $response->assertJsonStructure(['errors' => []]);
    }

    public function testShouldChangeValueInDatabase() {
        Passport::actingAs($this->testUser);

        $requestData = [
            'name' => 'Супер счета',
        ];

        $this->assertDatabaseMissing('account_types', ['name' => 'Супер счета']);

        $this->makeRequest($requestData);

        $this->assertDatabaseHas('account_types', ['name' => 'Супер счета']);
    }

    public function testShouldDontHavePermissionsToAnotherUserIdData() {
        Passport::actingAs($this->testUser);

        $requestData = [
            'name' => 'Супер счета',
        ];

        $response = $this->makeRequestToAnotherAccountType($requestData);

        $response->assertForbidden();
        $response->assertExactJson(['message' => 'Нет доступа к данному типу счета']);
        $this->assertDatabaseMissing('account_types', ['name' => $requestData['name'], 'id' => $this->testAccountTypeAnother->id]);
    }

    private function makeRequest(array $requestData): TestResponse {
        return $this->json('PUT', "/api/account_types/{$this->testAccountType->id}", $requestData);
    }

    private function makeRequestToAnotherAccountType(array $requestData): TestResponse {
        return $this->json('PUT', "/api/account_types/{$this->testAccountTypeAnother->id}", $requestData);
    }
}