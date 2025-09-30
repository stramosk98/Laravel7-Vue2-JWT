<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('app');
    }

    public function dashboard(Request $request)
    {
        return response()->json([
            'message' => 'Bem-vindo ao painel!',
            'user' => $request->user()
        ]);
    }
}
