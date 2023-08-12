<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthorArticles extends Controller
{
    /**
     * Return all active articles form the author
     * If current user is admin or moderator show also and non-active articles
     *
     * @param User $author
     * @return Response
     */
    function index(User $author): Response
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user && $user->hasAnyRole('admin', 'moderator')) {
            $articles = $author->articles()->paginate();
        } else {
            $articles = $author->articles()->where('active', true)->paginate();
        }

        return ArticleResource::collection($articles)
            ->response()->setStatusCode(Response::HTTP_OK);
    }
}
