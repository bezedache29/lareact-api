<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        $getInfo = Socialite::driver($provider)->user();

        $user = $this->createUser($provider, $getInfo);

        Auth::login($user);

        return redirect('https://lareact.ripley.eu/login/google/' . $user->api_token);
    }

    private function createUser($provider, $getInfo)
    {
        // On check si le user a un provider_id
        $user = User::where('provider_id', $getInfo->id)->first();

        // Si le user n'a pas de provider_id, on le cré
        if (!$user) {
            $user = User::create([
                'pseudo' => $getInfo->name,
                'email' => $getInfo->email,
                'provider' => $provider,
                'provider_id' => $getInfo->id,
                'api_token' => Str::random(60)
            ]);
        }

        return $user;
    }
}
