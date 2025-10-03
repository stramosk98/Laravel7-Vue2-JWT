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

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->generateApiToken();
            
            $userWithPermissions = User::with('permissions')->find($user->id);

            return response()->json([
                'user' => $userWithPermissions,
                'token' => $token,
                'message' => 'Login realizado com sucesso!'
            ]);
        }

        return response()->json([
            'message' => 'Credenciais inválidas'
        ], 401);
    }

    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->api_token = null;
            $request->user()->save();
        }

        return response()->json([
            'message' => 'Logout realizado com sucesso!'
        ]);
    }

    public function user(Request $request)
    {
        $user = User::with('permissions')->find($request->user()->id);
        return response()->json($user);
    }
}
