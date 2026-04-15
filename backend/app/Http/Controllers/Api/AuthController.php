<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function csrfToken(Request $request): JsonResponse
    {
        $request->session();

        return response()->json([
            'token' => csrf_token(),
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        return response()->json([
            'authenticated' => $user !== null,
            'user' => $user ? $this->serializeUser($user) : null,
        ]);
    }

    public function register(Request $request): JsonResponse
    {
        if (Auth::check()) {
            return response()->json([
                'message' => 'You are already signed in.',
                'authenticated' => true,
                'user' => $this->serializeUser($request->user()),
                'redirect_to' => '/index.html',
            ], 409);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
        ]);

        event(new Registered($user));

        Auth::login($user);
        $request->session()->regenerate();

        return response()->json([
            'message' => 'Registration completed.',
            'authenticated' => true,
            'user' => $this->serializeUser($user),
            'redirect_to' => '/index.html',
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        if (Auth::check()) {
            return response()->json([
                'message' => 'You are already signed in.',
                'authenticated' => true,
                'user' => $this->serializeUser($request->user()),
                'redirect_to' => '/index.html',
            ], 409);
        }

        $request->authenticate();
        $request->session()->regenerate();

        /** @var User $user */
        $user = $request->user();

        return response()->json([
            'message' => 'Signed in successfully.',
            'authenticated' => true,
            'user' => $this->serializeUser($user),
            'redirect_to' => '/index.html',
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        if (Auth::check()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return response()->json([
            'message' => 'Signed out successfully.',
            'authenticated' => false,
            'user' => null,
            'redirect_to' => '/index.html',
        ]);
    }

    private function serializeUser(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ];
    }
}
