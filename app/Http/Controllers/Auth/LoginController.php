<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('app');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if ($token = auth('api')->attempt($credentials)) {
            $user = auth('api')->user();
            $userWithPermissions = User::with('permissions')->find($user->id);

            return response()->json([
                'user' => $userWithPermissions,
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth('api')->factory()->getTTL() * 60,
                'message' => 'Login realizado com sucesso!'
            ]);
        }

        return response()->json([
            'message' => 'Credenciais inválidas'
        ], 401);
    }

    public function logout(Request $request)
    {
        auth('api')->logout();

        return response()->json([
            'message' => 'Logout realizado com sucesso!'
        ]);
    }

    public function user(Request $request)
    {
        $user = User::with('permissions')->find(auth('api')->user()->id);
        return response()->json($user);
    }

    public function refresh()
    {
        $token = auth('api')->refresh();
        
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'message' => 'Token renovado com sucesso!'
        ]);
    }
}
