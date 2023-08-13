<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    const JSON_STRUCTURE = [
        'data' => [
            'id',
            'login',
            'email',
            'first_name',
            'last_name',
            'patronymic',
            'created_at',
            'updated_at',
            'roles',
            'token_access',
            'token_type',
        ],
    ];

    /**
     * A basic feature test example.
     */
    public function testLoginEmail(): void
    {
        $password = fake()->password(8);
        $user = User::factory()->create(['password' => $password]);

        $payload = [
            'email' => $user->email,
            'password' => $password
        ];

        $response = $this->post(route('user.login'), $payload);

        $response->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure(self::JSON_STRUCTURE);

        $user->delete();
    }

    /**
     * A basic feature test example.
     */
    public function testLoginLogin(): void
    {
        $password = fake()->password(8);
        $user = User::factory()->create(['password' => $password]);

        $payload = [
            'login' => $user->login,
            'password' => $password
        ];

        $response = $this->post(route('user.login'), $payload);

        $response->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure(self::JSON_STRUCTURE);

        $user->delete();
    }

    /**
     * A basic feature test example.
     */
    public function testEmailLoginLogin(): void
    {
        $password = fake()->password(8);
        $user = User::factory()->create(['password' => $password]);

        $payload = [
            'email_login' => $user->login,
            'password' => $password
        ];

        $response = $this->post(route('user.login'), $payload);

        $response->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure(self::JSON_STRUCTURE);

        $user->delete();
    }

    public function testRegistration(): void
    {
        $password = fake()->password(8);
        $payload = [
            'login' => fake()->userName(),
            'email' => fake()->email(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'patronymic' => fake()->lastName(),
            'active' => true,
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $response = $this->post(route('user.register'), $payload);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(self::JSON_STRUCTURE);

        User::destroy($response->json('data.id'));
    }

    public function testIndex(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get(route('user'))
            ->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure(self::JSON_STRUCTURE);

        $user->delete();
    }
}
