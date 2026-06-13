<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')
            ->stateless()
            ->with(['prompt' => 'select_account'])
            ->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // Solo permite correos @ufpso.edu.co
        if (!str_ends_with($googleUser->getEmail(), '@ufpso.edu.co')) {
            return redirect('/login')->withErrors([
                'email' => 'Solo se permite el acceso con correo institucional @ufpso.edu.co'
            ]);
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            return redirect('/login')->withErrors([
                'email' => 'No existe una cuenta registrada con este correo institucional.'
            ]);
        }

        Auth::login($user);

        return redirect('/dashboard');
    }
}