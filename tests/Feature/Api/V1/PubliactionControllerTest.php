<?php

namespace Tests\Feature\Api\V1;

use App\Models\Article;
use App\Models\Publication;
use App\Models\Rubric;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class PubliactionControllerTest extends TestCase
{
    const JSON_PUBLICATION_STRUCTURE = [
        'data' => [
            'article',
            'rubric',
            'created_at',
            'updated_at',
        ]
    ];

    const JSON_COLLECTION_STRUCTURE = [
        'data' => [
            '*' => [
                'article',
                'rubric',
                'created_at',
                'updated_at',
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

    public function testViewAllPublications(): void
    {
        $this->getJson(route('publications.index'))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(self::JSON_COLLECTION_STRUCTURE);
    }

    public function testShowPublication(): void
    {
        $author = User::factory()->create(['active' => true]);
        $article = Article::factory()->create(['active' => true, 'user_id' => $author->id]);
        $rubric = Rubric::factory()->create();
        $publication = Publication::factory()->create(['article_id' => $article->id, 'rubric_id' => $rubric->id]);

        $this->getJson(route('publications.show', ['publication' => $publication->id]))
            ->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure(self::JSON_PUBLICATION_STRUCTURE);

        $author->delete();
        $rubric->delete();
    }

    public function testStorePublicationAuth(): void
    {
        $author = User::factory()->create(['active' => true]);
        $author->givePermissionTo('create publications');
        $article = Article::factory()->create(['active' => true, 'user_id' => $author->id]);
        $rubric = Rubric::factory()->create();
        $publication = Publication::factory()->make(['article_id' => $article->id, 'rubric_id' => $rubric->id]);

        $payload = $publication->toArray();

        $this->actingAs($author)
            ->postJson(route('publications.store'), $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(self::JSON_PUBLICATION_STRUCTURE);

        $author->delete();
        $rubric->delete();
    }

    public function testStorePublication(): void
    {
        $author = User::factory()->create(['active' => true]);
        $article = Article::factory()->create(['active' => true, 'user_id' => $author->id]);
        $rubric = Rubric::factory()->create();
        $publication = Publication::factory()->make(['article_id' => $article->id, 'rubric_id' => $rubric->id]);

        $payload = $publication->toArray();

        $this->postJson(route('publications.store'), $payload)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $author->delete();
        $rubric->delete();
    }

    public function testDestroyPublicationAuth(): void
    {
        $author = User::factory()->create(['active' => true]);
        $author->givePermissionTo('delete publications');
        $article = Article::factory()->create(['active' => true, 'user_id' => $author->id]);
        $rubric = Rubric::factory()->create();
        $publication = Publication::factory()->create(['article_id' => $article->id, 'rubric_id' => $rubric->id]);

        $this->actingAs($author)
            ->deleteJson(route('publications.destroy', ['publication' => $publication->id]))
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $author->delete();
        $rubric->delete();
    }

    public function testDestroyPublication(): void
    {
        $author = User::factory()->create(['active' => true]);
        $article = Article::factory()->create(['active' => true, 'user_id' => $author->id]);
        $rubric = Rubric::factory()->create();
        $publication = Publication::factory()->create(['article_id' => $article->id, 'rubric_id' => $rubric->id]);

        $this->deleteJson(route('publications.destroy', ['publication' => $publication->id]))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $author->delete();
        $rubric->delete();
    }
}
