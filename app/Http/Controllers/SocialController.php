<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginProviderRequest;
use App\Models\User;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirectToProvider($provider): JsonResponse
    {
        $validated = $this->validateProvider($provider);
        if (!is_null($validated)) {
            return $validated;
        }

        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function handleProviderCallback($provider): JsonResponse
    {
        $validated = $this->validateProvider($provider);

        if (!is_null($validated)) {
            return $validated;
        }

        try {
            $user = Socialite::driver($provider)->stateless()->user();
        } catch (ClientException $exception) {
            return $this->errorResponse('Invalid credentials provided.');
        }

        return response()->json($user);
    }

    private function validateProvider($provider): JsonResponse
    {
        if ($provider !== 'google') {
            return $this->errorResponse('Please login using google');
        }

        return $this->errorResponse();
    }

    public function loginWithGoogle(LoginProviderRequest $request): JsonResponse
    {
        $client = new \Google_Client(['client_id' => env('GOOGLE_CLIENT_ID')]);
        $response = $client->verifyIdToken($request->token);

        if ($response) {
            return $this->storeAndReturnToken($response);
        }

        return $this->errorResponse('Invalid credentials provided.', 400);
    }

    public function storeAndReturnToken($response): JsonResponse
    {
        $user = User::where('email', $response['email'])->first();

        if (!$user) {
            $userCreated = User::firstOrCreate([
                'name' => $response['name'],
                'email' => $response['email'],
                'email_verified_at' => now(),
            ]);
            $response['user_id'] = $userCreated->id ?? '';
            return $this->successResponse([]);
        }
        $response['user_id'] = $user->id ?? '';
        return $this->successResponse([]);
    }
}
