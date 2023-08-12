<?php

namespace Tests\Feature\Api\V1;

use App\Models\Article;
use App\Models\Publication;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class AuthorArticlesControllerTest extends TestCase
{
    const JSON_ARTICLE_STRUCTURE = ArticleControllerTest::JSON_ARTICLE_STRUCTURE;
    const JSON_COLLECTION_STRUCTURE = ArticleControllerTest::JSON_COLLECTION_STRUCTURE;

    /**
     * A basic feature test example.
     */
    public function testViewAuthorArticles(): void
    {
        $author = User::factory()->create(['active' => true]);
        $article = Article::factory()->create(['active' => true, 'user_id' => $author->id]);

        $this->get(route('authors.articles.index', ['author' => $author->id]))
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure(self::JSON_COLLECTION_STRUCTURE);

        $article->delete();
        $author->delete();
    }
}