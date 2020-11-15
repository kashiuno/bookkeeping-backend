<?php

namespace Tests\Api\Bookkeeping\AccountType;

use App\Models\Bookkeeping\AccountType;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Laravel\Passport\Passport;
use Tests\TestCase;

class DeleteAccountTypesTest extends TestCase
{
    use RefreshDatabase;

    private User $testUser;
    private AccountType $testAccountType;
    private AccountType $testAccountTypeAnother;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('migrate');
        Artisan::call('passport:install');
        Artisan::call('db:seed', ['--class' => 'UserSeeder']);
        Artisan::call('db:seed', ['--class' => 'AccountTypeSeeder']);
        $this->testUser = User::where('email', 'taigusiobaka@gmail.com')
                              ->first()
        ;
        $this->testAccountType = AccountType::where('user_id', $this->testUser->id)
                                            ->first()
        ;
        $this->testAccountTypeAnother = AccountType::where('user_id', '!=', $this->testUser->id)
                                                   ->first()
        ;
    }

    public function testShouldDeleteRowWithExactAccountType()
    {
        Passport::actingAs($this->testUser);

        $this->assertDatabaseHas('account_types', ['id' => $this->testAccountType->id]);

        $this->json('DELETE', "/api/account_types/{$this->testAccountType->id}");

        $this->assertDatabaseMissing('account_types', ['id' => $this->testAccountType->id]);
    }

    public function testShouldReturnExactMessageIfTryWasSuccess() {
        Passport::actingAs($this->testUser);

        $response = $this->json('DELETE', "/api/account_types/{$this->testAccountType->id}");

        $response->assertExactJson(['message' => 'Удаление произошло успешно']);
    }

    public function testShouldReturn_200CodeIfTryWasSuccess() {
        Passport::actingAs($this->testUser);

        $response = $this->json('DELETE', "/api/account_types/{$this->testAccountType->id}");

        $response->assertOk();
    }

    public function testShouldFailWhenTryDeleteNotOwnAccountType()
    {
        Passport::actingAs($this->testUser);

        $this->assertDatabaseHas('account_types', ['id' => $this->testAccountTypeAnother->id]);

        $this->json('DELETE', "/api/account_types/{$this->testAccountTypeAnother->id}");

        $this->assertDatabaseHas('account_types', ['id' => $this->testAccountTypeAnother->id]);
    }
}