<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    public function login(Request $request)
    {
        $incomingFields = $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if (auth()->attempt(['email' => $incomingFields['email'], 'password' => $incomingFields['password']])) {
            $request->session()->regenerate();
        }
        return redirect('/');
    }

    public function logout()
    {
        auth()->logout();
        return redirect('/');
    }

    public function register(Request $request)
    {
        $incomingFields = $request->validate([
            'name' => ['required', 'min:3', 'max:30'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'min:6', 'max:20']
        ]);

        $incomingFields['password'] = bcrypt($incomingFields['password']);
        $user = User::create($incomingFields);
        auth()->login($user);
        return redirect('/');
    }
}
