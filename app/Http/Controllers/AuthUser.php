<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use App\Http\Resources\UserCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AuthUser extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return Response
     */
    public function login(): Response
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return new Response([], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return Response
     */
    protected function respondWithToken(string $token): Response
    {
        return new Response([
            'access_token' => $token,
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

}
