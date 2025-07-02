<?php

<<<<<<< HEAD
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
=======
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Factory;
>>>>>>> 67eb95f44ae58db7ce3a1ff1ee249f01ccb1cbc7

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

<<<<<<< HEAD
require __DIR__.'/auth.php';
=======
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

#firebase
Route::post('/login-google', function (Request $request) {
    $idToken = $request->input('id_token');

    $firebase = (new Factory)
        ->withServiceAccount(config('firebase.credentials.file'));
    $auth = $firebase->createAuth();

    try {
        $verifiedIdToken = $auth->verifyIdToken($idToken);
        $firebaseUserId = $verifiedIdToken->claims()->get('sub');
        $email = $verifiedIdToken->claims()->get('email');

        // Crear o buscar usuario
        $user = User::firstOrCreate(
            ['email' => $email],
            ['firebase_uid' => $firebaseUserId, 'name' => $email]
        );

        // Generar token (si usas Sanctum o Passport)
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => $user,
        ]);
    } catch (\Throwable $e) {
        return response()->json(['error' => 'Token inválido: ' . $e->getMessage()], 401);
    }
});

require __DIR__.'/auth.php';

>>>>>>> 67eb95f44ae58db7ce3a1ff1ee249f01ccb1cbc7
