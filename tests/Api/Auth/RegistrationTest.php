<?php

namespace Tests\Api\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    private const TEST_VALID_NAME = 'taigusio';
    private const TEST_VALID_EMAIL = 'taigus@gmail.com';
    private const TEST_VALID_PASSWORD = 'd549769e';
    private const TEST_VALID_PASSWORD_CONFIRMATION = 'd549769e';

    private const TEST_INVALID_EXISTING_NAME = 'kashiuno';

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
        Artisan::call('passport:install');
        Artisan::call('db:seed', ['--class' => 'UserSeeder']);
    }

    public function testShouldReturn_200ResponseCodeIfTryWasSuccess()
    {
        $credentials = [
            'name' => self::TEST_VALID_NAME,
            'email' => self::TEST_VALID_EMAIL,
            'password' => self::TEST_VALID_PASSWORD,
            'password_confirmation' => self::TEST_VALID_PASSWORD_CONFIRMATION,
        ];

        $this->makeRequest($credentials)
            ->assertStatus(Response::HTTP_OK);
    }

    public function testShouldReturnSuccessFullMessageIfTryWasSuccess()
    {
        $credentials = [
            'name' => self::TEST_VALID_NAME,
            'email' => self::TEST_VALID_EMAIL,
            'password' => self::TEST_VALID_PASSWORD,
            'password_confirmation' => self::TEST_VALID_PASSWORD_CONFIRMATION,
        ];

        $this->makeRequest($credentials)
            ->assertExactJson([
                'message' => 'You were successfully registered. Use your email and password to sign in',
            ]);
    }

    public function testShouldReturnErrorsAndMessagesIfTryWasUnsuccess()
    {
        $credentials = [
            'name' => '',
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
        ];

        $this->makeRequest($credentials)
            ->assertJsonFragment([
                'message' => 'The given data was invalid.'
            ])
            ->assertJsonFragment([
                'errors' => [
                    'name' => ['Field name is required'],
                    'email' => ['Field email is required'],
                    'password' => ['Field password is required'],
                ]
            ]);
    }

    public function testShouldReturnErrorAboutUniqueIfNameJustExist()
    {
        $credentials = [
            'name' => self::TEST_INVALID_EXISTING_NAME,
            'email' => self::TEST_VALID_EMAIL,
            'password' => self::TEST_VALID_PASSWORD,
            'password_confirmation' => self::TEST_VALID_PASSWORD_CONFIRMATION,
        ];

        $this->makeRequest($credentials)
            ->assertJsonFragment([
                'errors' => [
                    'name' => ['Name must be an unique']
                ]
            ]);
    }

    public function testShouldReturn_422ResponseIfValidationFail()
    {
        $credentials = [
            'name' => self::TEST_INVALID_EXISTING_NAME,
            'email' => self::TEST_VALID_EMAIL,
            'password' => self::TEST_VALID_PASSWORD,
            'password_confirmation' => self::TEST_VALID_PASSWORD_CONFIRMATION,
        ];

        $this->makeRequest($credentials)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    private function makeRequest(array $credentials): TestResponse
    {
        return $this->json('POST', '/api/register', $credentials);
    }
}
