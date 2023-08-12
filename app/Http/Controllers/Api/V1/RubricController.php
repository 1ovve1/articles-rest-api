<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rubrics\StoreRubricRequest;
use App\Http\Requests\Rubrics\UpdateRubricRequest;
use App\Http\Resources\RubricResource;
use App\Models\Rubric;
use Symfony\Component\HttpFoundation\Response;

class RubricController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')
            ->only(['store', 'update', 'destroy']);

        $this->authorizeResource(Rubric::class, 'rubric');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $rubrics = Rubric::onlyActive()->paginate();

        return RubricResource::collection($rubrics)
            ->response()->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRubricRequest $request): Response
    {
        $payload = $request->validated();

        $rubric = Rubric::create($payload);

        return (new RubricResource($rubric))
            ->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Rubric $rubric)
    {
        return (new RubricResource($rubric))
            ->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRubricRequest $request, Rubric $rubric)
    {
        $payload = $request->validated();
        try {
            $rubric->updateOrFail($payload);

            return (new RubricResource($rubric))
                ->response()->setStatusCode(Response::HTTP_ACCEPTED);
        } catch (\Throwable $e) {
            return response()->json(['error' => ['message' => 'failed to update']], Response::HTTP_CONFLICT);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rubric $rubric)
    {
        $rubric->delete();

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
