<?php

namespace Tests\Feature\Api\V1;

use App\Models\Article;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    const JSON_ARTICLE_STRUCTURE = [
        'data' => [
            'id',
            'user_id',
            'slug',
            'title',
            'text',
            'image_path',
            'active',
            'created_at',
            'updated_at'
        ]
    ];
    const JSON_COLLECTION_STRUCTURE = [
        'data' => [
            '*' => [
                'id',
                'user_id',
                'slug',
                'title',
                'text',
                'image_path',
                'active',
                'created_at',
                'updated_at'
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

    public function testViewAllArticles(): void
    {
        $this->getJson(route('articles.index'))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(self::JSON_COLLECTION_STRUCTURE);
    }

    public function testShowArticle(): void
    {
        $article = Article::factory()->create(['active' => true]);

        $this->getJson(route('articles.show', ['article' => $article->slug]))
            ->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure(self::JSON_ARTICLE_STRUCTURE);


        $article->delete();
    }

    public function testStoreArticleWithPermissions(): void
    {
        $user = User::factory()->create(['active' => true]);
        $user->givePermissionTo('create articles');

        $article = Article::factory()->make();
        $payload = $article->toArray();

        $this->actingAs($user)
            ->postJson(route('articles.store'), $payload)
            ->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure(self::JSON_ARTICLE_STRUCTURE);

        $user->delete();
        $article->delete();
    }

    public function testStoreArticleWithoutPermission(): void
    {
        $user = User::factory()->create(['active' => true]);

        $article = Article::factory()->make();
        $payload = $article->toArray();

        $this->actingAs($user)
            ->postJson(route('articles.store'), $payload)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $user->delete();
        $article->delete();
    }


    public function testUpdateArticleWithPermission(): void
    {
        $user = User::factory()->create(['active' => true]);
        $user->givePermissionTo('edit articles');

        $article = Article::factory()->create(['active' => true]);
        $newArticle = Article::factory()->make();
        $payload = $newArticle->toArray();

        $this->actingAs($user)
            ->putJson(route('articles.update', ['article' => $article->slug]), $payload)
            ->assertStatus(Response::HTTP_ACCEPTED)
            ->assertJsonStructure(self::JSON_ARTICLE_STRUCTURE);

        $user->delete();
        $article->delete();
    }

    public function testUpdateArticleWithoutPermission(): void
    {
        $user = User::factory()->create(['active' => true]);

        $article = Article::factory()->create(['active' => true]);
        $newArticle = Article::factory()->make();
        $payload = $newArticle->toArray();

        $this->actingAs($user)
            ->putJson(route('articles.update', ['article' => $article->slug]), $payload)
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $user->delete();
        $article->delete();
    }

    public function testDestroyArticleWithPermission(): void
    {
        $user = User::factory()->create(['active' => true]);
        $user->givePermissionTo('delete articles');

        $article = Article::factory()->create(['active' => true]);

        $this->actingAs($user)
            ->deleteJson(route('articles.destroy', ['article' => $article->slug]))
            ->assertStatus(Response::HTTP_NO_CONTENT);

        $this->assertNull(Article::find($article->id));
        $user->delete();
    }

    public function testDestroyArticleWithoutPermission(): void
    {
        $user = User::factory()->create(['active' => true]);

        $article = Article::factory()->create(['active' => true]);

        $this->actingAs($user)
            ->deleteJson(route('articles.destroy', ['article' => $article->slug]))
            ->assertStatus(Response::HTTP_UNAUTHORIZED);

        $this->assertNotNull(Article::find($article->id));
        $article->delete();
        $user->delete();
    }
}
