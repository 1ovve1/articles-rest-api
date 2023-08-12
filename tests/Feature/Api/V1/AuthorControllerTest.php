<?php

namespace Tests\Feature\Api\V1;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthorControllerTest extends TestCase
{
    const USER_STRUCTURE = [
        'data' => [
            'id',
            'login',
            'first_name',
            'last_name',
            'patronymic ',
            'active',
            'created_at',
        ],
    ];

    const ALL_USERS_STRUCTURE = [
        'data' => [
            '*' => [
                'id',
                'login',
                'first_name',
                'last_name',
                'patronymic ',
                'active',
                'created_at',
            ],
        ],
        'links' => [
            'first',
            'last',
            'prev',
            'next'
        ],
        'meta' => [
            'current_page',
            'from',
            'last_page',
            'links',
            'path',
            'per_page',
            'to',
            'total',
        ]

    ];
    protected mixed $adminUser;
    protected mixed $moderatorUser;
    protected mixed $writerUser;


    function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->adminUser = User::factory()->create();
        $this->adminUser->assignRole('admin');

        $this->moderatorUser = User::factory()->create();
        $this->moderatorUser->assignRole('moderator');

        $this->writerUser = User::factory()->create();
        $this->writerUser->assignRole('writer');

        $writerUser = User::factory()->create();
        $writerUser->assignRole('writer');

        $this->app->make(\Spatie\Permission\PermissionRegistrar::class)->registerPermissions();
    }

    protected function tearDown(): void
    {
        $this->adminUser->delete();
        $this->moderatorUser->delete();
        $this->writerUser->delete();

        parent::tearDown();
    }


    /**
     * Get authors as admin
     */
    public function testViewAllAuthors(): void
    {
        $this->getJson(route('authors.index'))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(self::ALL_USERS_STRUCTURE);
    }

    public function testViewArticle(): void
    {
        $this->getJson(route('authors.show', ['author' => 1]))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(self::USER_STRUCTURE);
    }

    public function testAuthorCreationAdmin(): void
    {
        $user = User::factory()->make();

        $password = fake()->password(8);
        $payload = [
            ...$user->toArray(),
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $this->actingAs($this->adminUser)
            ->postJson(route('authors.store'), $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(self::USER_STRUCTURE);

        User::find($user->id)?->delete();
    }

    public function testAuthorCreationAdminValidation(): void
    {
        $user = User::factory()->make();

        $password = fake()->password(2, 2);
        $payload = [
            ...$user->toArray(),
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $this->actingAs($this->adminUser)
            ->postJson(route('authors.store'), $payload)
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        User::find($user->id)?->delete();
    }

    public function testAuthorCreationOthers(): void
    {
        $password = fake()->password(8);
        $payload = [
            'login' => fake()->unique()->userName(),
            'email' => fake()->unique()->email(),
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'patronymic' => fake()->lastName(),
            'password' => $password,
            'password_confirmation' => $password,
            'active' => true
        ];

        $this->actingAs($this->writerUser)
            ->postJson(route('authors.store'), $payload)
            ->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function testAuthorUpdateAdmin(): void
    {
        $user = User::factory()->create();

        $payload = [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'patronymic' => fake()->lastName(),
            'email' => fake()->email(),
            'login' => fake()->userName(),
            'active' => fake()->boolean(),
        ];

        $this->actingAs($this->adminUser)
            ->putJson(route('authors.update', ['author' => $user->id]), $payload)
            ->assertStatus(Response::HTTP_ACCEPTED);

        $user->delete();
    }

    public function testAuthorUpdateOthers(): void
    {
        $user = User::factory()->create();

        $payload = [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'patronymic' => fake()->lastName(),
            'email' => fake()->email(),
            'login' => fake()->userName(),
            'active' => fake()->boolean(),
        ];

        $this->actingAs($this->writerUser)
            ->putJson(route('authors.update', ['author' => $user->id]), $payload)
            ->assertStatus(Response::HTTP_FORBIDDEN);

        $user->delete();
    }

    public function testAdminDestroyAuthor(): void
    {
        $user = User::factory()->create();

        $this->actingAs($this->adminUser)
            ->deleteJson(route('authors.destroy', ['author' => $user->id]))
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $user = User::find($user->id);
        $this->assertNull($user);
    }
}

