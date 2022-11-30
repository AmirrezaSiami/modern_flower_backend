<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
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
     * @throws ValidationException
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {

        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        var_dump($token);die;

        return $this->respondWithToken($token);
    }

    protected function respondWithToken($token): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'user' => auth()->user(),
            'expires_in' => auth()->factory()->getTTL() * 60 * 24
        ]);
    }

    /**
     * @throws ValidationException
     */
    public function _____login(Request $request): UserResource
    {

//        return new UserResource(['sss' => 'fdfds']);

        // Validate passed parameters
        $this->validate($request, [
            'email' => 'required',
            'password' => 'required'
        ]);

        // Get the user with the email
        $user = User::where('email', $request['email'])->first();
        if (!isset($user)) {
            return new UserResource([
                'status' => false,
                'message' => 'User does not exist with this email.'
            ]);
        }

        // confirm that the password matches
        if (!Hash::check($request['password'], $user['password'])) {
            return new UserResource(
                [
                    'status' => false,
                    'message' => 'Incorrect user password.'
                ]
            );
        }

        // Generate Token
        $token = $user->createToken('AuthToken')->accessToken;

        // Add Generated token to user column
        User::where('email', $request['email'])->update(array('api_token' => $token));

        return new UserResource(
            [
                'status' => true,
                'message' => 'User login successfully',
                'data' => [
                    'user' => $user,
                    'api_token' => $token
                ]
            ]
        );

    }

}
