<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Publications\StorePublicationRequest;
use App\Http\Resources\PublicationResource;
use App\Models\Publication;
use Symfony\Component\HttpFoundation\Response;

class PublicationController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum'])
            ->only(['store', 'destroy']);
        $this->authorizeResource(Publication::class, 'publication');
    }


    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $publications = Publication::paginate();

        return PublicationResource::collection($publications)
            ->response()->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePublicationRequest $request): Response
    {
        $payload = $request->validated();

        $publication = Publication::create($payload);

        return (new PublicationResource($publication))
            ->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Publication $publication): Response
    {
        return (new PublicationResource($publication))
            ->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publication $publication): Response
    {
        $publication->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
