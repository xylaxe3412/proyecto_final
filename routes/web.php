<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Redirigir a Google
Route::get('/auth/google', function () {
    return Socialite::driver('google')->redirect();
})->name('google.login');

// Callback
Route::get('/auth/google/callback', function () {
    try {
        $googleUser = Socialite::driver('google')->stateless()->user();

        // Buscar o registrar usuario
        $user = \App\Models\User::firstOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'email_verified_at' => now(), // opcional
                'password' => bcrypt(uniqid()) // para cumplir con campo requerido
            ]
        );

        Auth::login($user);
        return redirect('/dashboard'); // o página de bienvenida

    } catch (\Exception $e) {
        return redirect('/')->with('error', 'Error al autenticar con Google.');
    }
});

require __DIR__.'/auth.php';

