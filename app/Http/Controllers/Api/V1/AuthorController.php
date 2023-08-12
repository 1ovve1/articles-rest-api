<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Authors\StoreAuthorRequest;
use App\Http\Requests\Authors\UpdateAuthorRequest;
use App\Http\Resources\AuthorResource;
use App\Models\Publication;
use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

class AuthorController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum'])
            ->only(['store', 'update', 'destroy']);
        $this->authorizeResource(User::class, 'author');
    }


    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $users = User::paginate();

        return AuthorResource::collection($users)
            ->response()->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAuthorRequest $request, User $user): Response
    {
        $payload = $request->validated();
        $user = User::createWriter($payload);

        return (new AuthorResource($user))
            ->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $author): Response
    {
        return (new AuthorResource($author))
            ->response()->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAuthorRequest $request, User $author): Response
    {
        $payload = $request->validated();
        try {
            $author->updateOrFail($payload);

            return (new AuthorResource($author))
                ->response()->setStatusCode(Response::HTTP_ACCEPTED);
        } catch (\Throwable $e) {
            return response()->json(['error' => ['message' => 'failed to update']], Response::HTTP_CONFLICT);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $author): Response
    {
        $author->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
