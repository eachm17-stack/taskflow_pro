<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_via_api_and_receives_cors_headers(): void
    {
        $origin = 'https://frontend-react-woad-eight.vercel.app';

        $response = $this->withHeaders([
            'Origin' => $origin,
            'Accept' => 'application/json',
        ])->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'testuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertHeader('Access-Control-Allow-Origin', $origin)
            ->assertJsonStructure([
                'status',
                'message',
                'user' => ['id', 'name', 'email'],
                'token',
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'testuser@example.com',
        ]);
    }

    public function test_user_can_login_via_api_and_receives_cors_headers(): void
    {
        $origin = 'https://frontend-react-woad-eight.vercel.app';
        $user = User::factory()->create([
            'email' => 'loginuser@example.com',
            'password' => 'secret1234',
        ]);

        $response = $this->withHeaders([
            'Origin' => $origin,
            'Accept' => 'application/json',
        ])->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'secret1234',
        ]);

        $response->assertStatus(200)
            ->assertHeader('Access-Control-Allow-Origin', $origin)
            ->assertJsonStructure([
                'status',
                'user',
                'token',
            ]);
    }

    public function test_projects_endpoint_returns_data_with_cors_headers(): void
    {
        $origin = 'https://frontend-react-woad-eight.vercel.app';

        $response = $this->withHeaders([
            'Origin' => $origin,
            'Accept' => 'application/json',
        ])->getJson('/api/projects');

        $response->assertStatus(200)
            ->assertHeader('Access-Control-Allow-Origin', $origin)
            ->assertJsonIsArray();
    }

    public function test_validation_errors_preserve_cors_headers(): void
    {
        $origin = 'https://frontend-react-woad-eight.vercel.app';

        $response = $this->withHeaders([
            'Origin' => $origin,
            'Accept' => 'application/json',
        ])->postJson('/api/register', [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123',
        ]);

        $response->assertStatus(422)
            ->assertHeader('Access-Control-Allow-Origin', $origin)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_server_error_preserves_cors_headers(): void
    {
        $origin = 'https://frontend-react-woad-eight.vercel.app';
        \Illuminate\Support\Facades\Route::get('/api/test-server-error', function () {
            throw new \Exception('Simulated 500 error');
        });

        $response = $this->withHeaders([
            'Origin' => $origin,
            'Accept' => 'application/json',
        ])->getJson('/api/test-server-error');

        $response->assertStatus(500)
            ->assertHeader('Access-Control-Allow-Origin', $origin);
    }
}

