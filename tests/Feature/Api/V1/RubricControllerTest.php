<?php

namespace Tests\Feature\Api\V1;

use App\Models\Rubric;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class RubricControllerTest extends TestCase
{
    const JSON_RUBRIC_STRUCTURE = [
        'data' => [
            'id',
            'name',
            'created_at',
        ]
    ];
    const JSON_COLLECTION_STRUCTURE = [
        'data' => [
            '*' => [
                'id',
                'name',
                'created_at',
            ]
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

    public function testViewAllRubrics(): void
    {
        $this->getJson(route('rubrics.index'))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(self::JSON_COLLECTION_STRUCTURE);
    }

    public function testShowRubric(): void
    {
        $rubric = Rubric::factory()->create(['active' => true]);

        $this->getJson(route('rubrics.show', ['rubric' => $rubric->id]))
            ->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure(self::JSON_RUBRIC_STRUCTURE);


        $rubric->delete();
    }

    public function testStoreRubricWithPermissions(): void
    {
        $user = User::factory()->create(['active' => true]);
        $user->givePermissionTo('create rubrics');

        $rubric = Rubric::factory()->make();
        $payload = $rubric->toArray();

        $this->actingAs($user)
            ->postJson(route('rubrics.store'), $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(self::JSON_RUBRIC_STRUCTURE);

        $user->delete();
        $rubric->delete();
    }

    public function testStoreRubricWithoutPermission(): void
    {
        $user = User::factory()->create(['active' => true]);

        $rubric = Rubric::factory()->make();
        $payload = $rubric->toArray();

        $this->actingAs($user)
            ->postJson(route('rubrics.store'), $payload)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $user->delete();
        $rubric->delete();
    }


    public function testUpdateRubricWithPermission(): void
    {
        $user = User::factory()->create(['active' => true]);
        $user->givePermissionTo('edit rubrics');

        $rubric = Rubric::factory()->create(['active' => true]);
        $newRubric = Rubric::factory()->make();
        $payload = $newRubric->toArray();

        $this->actingAs($user)
            ->putJson(route('rubrics.update', ['rubric' => $rubric->id]), $payload)
            ->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure(self::JSON_RUBRIC_STRUCTURE);

        $user->delete();
        $rubric->delete();
    }

    public function testUpdateRubricWithoutPermission(): void
    {
        $user = User::factory()->create(['active' => true]);

        $rubric = Rubric::factory()->create(['active' => true]);
        $newRubric = Rubric::factory()->make();
        $payload = $newRubric->toArray();

        $this->actingAs($user)
            ->putJson(route('rubrics.update', ['rubric' => $rubric->id]), $payload)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $user->delete();
        $rubric->delete();
    }

    public function testDestroyRubricWithPermission(): void
    {
        $user = User::factory()->create(['active' => true]);
        $user->givePermissionTo('delete rubrics');

        $rubric = Rubric::factory()->create(['active' => true]);

        $this->actingAs($user)
            ->deleteJson(route('rubrics.destroy', ['rubric' => $rubric->id]))
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertNull(Rubric::find($rubric->id));
        $user->delete();
    }

    public function testDestroyRubricWithoutPermission(): void
    {
        $user = User::factory()->create(['active' => true]);

        $rubric = Rubric::factory()->create(['active' => true]);

        $this->actingAs($user)
            ->deleteJson(route('rubrics.destroy', ['rubric' => $rubric->id]))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $this->assertNotNull(Rubric::find($rubric->id));
        $rubric->delete();
        $user->delete();
    }
}
