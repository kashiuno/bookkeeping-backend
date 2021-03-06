<?php

namespace Tests\Api\Auth;

use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private Collection $users;

    private string $testPassword;

    private string $testEmail;

    private User $testUser;

    protected function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
        Artisan::call('passport:install');
        Artisan::call('db:seed', ['--class' => 'UserSeeder']);
        $this->testEmail = 'taigusiobaka@gmail.com';
        $this->testPassword = 'asdasd';
        $this->testUser = User::where('email', 'taigusiobaka@gmail.com')->first();
    }

    public function testShouldReturn_401ResponseIfUserNotExist()
    {
        $credentials = [
            'email' => $this->testEmail,
            'password' => $this->testPassword,
        ];

        $this->testUser->delete();

        $this->makeRequest($credentials)->assertUnauthorized();
    }

    public function testShouldReturnErrorsAndMessageInResponseIfUserNotExist()
    {
        $credentials = [
            'email' => $this->testEmail,
            'password' => $this->testPassword,
        ];

        $this->testUser->delete();

        $this->makeRequest($credentials)
            ->assertExactJson([
                'message' => 'Логин или пароль не верные',
            ]);
    }

    public function testShouldReturn_401ResponseIfWrongPassword()
    {
        $credentials = [
            'email' => $this->testEmail,
            'password' => 'password',
        ];

        $this->makeRequest($credentials)->assertUnauthorized();
    }

    public function testShouldReturnErrorsAndMessageInResponseIfWrongPassword()
    {
        $credentials = [
            'email' => $this->testEmail,
            'password' => 'password',
        ];

        $this->makeRequest($credentials)
            ->assertExactJson([
                'message' => 'Логин или пароль не верные',
            ]);
    }

    public function testShouldReturn_200StatusIfLoginWasSuccess()
    {
        $credentials = [
            'email' => $this->testEmail,
            'password' => $this->testPassword,
        ];

        $this->makeRequest($credentials)->assertOk();
    }

    public function testShouldReturnTokenTypeFieldIfLoginWasSuccess()
    {
        $credentials = [
            'email' => $this->testEmail,
            'password' => $this->testPassword,
        ];

        $this->makeRequest($credentials)
            ->assertJsonFragment([
                'token_type' => 'Bearer'
            ]);
    }

    public function testShouldReturnTokenFieldIfLoginWasSuccess()
    {
        $credentials = [
            'email' => $this->testEmail,
            'password' => $this->testPassword,
        ];

        $this->makeRequest($credentials)
            ->assertJsonStructure([
                'token',
            ]);
    }

    public function testShouldReturnExpiresAtIfLoginWasSuccess()
    {
        $credentials = [
            'email' => $this->testEmail,
            'password' => $this->testPassword,
        ];

        $this->makeRequest($credentials)
            ->assertJsonStructure([
                'expires_at',
            ]);
    }

    private function makeRequest(array $credentials) {
        return $this->json('POST', '/api/login', $credentials);
    }
}
