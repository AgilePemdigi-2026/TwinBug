<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {
        User::create($request->safe()->only(['name', 'email', 'role', 'password']));

        return to_route('login')
            ->with('status', 'Akun berhasil dibuat. Silakan masuk.')
            ->withInput($request->only('email'));
    }
}
