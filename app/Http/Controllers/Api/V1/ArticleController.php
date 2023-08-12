<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Articles\StoreArticleRequest;
use App\Http\Requests\Articles\UpdateArticleRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')
            ->only(['store', 'update', 'destroy']);

        $this->authorizeResource(Article::class, 'article');
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user && $user->hasAnyRole('admin', 'moderator')) {
            $articles = Article::paginate();
        } else {
            $articles = Article::onlyActive()->paginate();
        }

        return ArticleResource::collection($articles)
            ->response()->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArticleRequest $request)
    {
        $payload = $request->validated();

        $article = Article::create($payload);

        return (new ArticleResource($article))
            ->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Article $article)
    {
        return (new ArticleResource($article))
            ->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        $payload = $request->validated();

        try {
            $article->updateOrFail($payload);

            return (new ArticleResource($article))
                ->response()->setStatusCode(Response::HTTP_ACCEPTED);
        } catch (\Throwable) {
            return response()->json(['error' => ['message' => 'failed to update']], Response::HTTP_CONFLICT);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Article $article)
    {
        $article->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
