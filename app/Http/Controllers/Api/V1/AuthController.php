<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\AuthUserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('index');
    }


    /**
     * @return Response
     */
    public function index(): Response
    {
        $user = Auth::user();

        return (new AuthUserResource($user))
            ->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }

    /**
     * @param RegisterUserRequest $registerUserRequest
     * @return Response
     */
    public function register(RegisterUserRequest $registerUserRequest): Response
    {
        $payload = $registerUserRequest->validated();

        $user = User::createWriter($payload);

        return (new AuthUserResource($user))
            ->response()->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * @param LoginUserRequest $loginUserRequest
     * @return Response
     */
    public function login(LoginUserRequest $loginUserRequest): Response
    {
        $payload = $loginUserRequest->validated();

        Auth::attempt($payload);

        $user = Auth::user();

        return (new AuthUserResource($user))
            ->response()->setStatusCode(Response::HTTP_ACCEPTED);
    }
}
