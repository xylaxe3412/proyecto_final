<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Kreait\Firebase\Auth as FirebaseAuth;
use Kreait\Firebase\Factory;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FormularioHabitoController;
use App\Http\Controllers\PreguntasFormController;
use App\Http\Controllers\HabitController;
use App\Http\Controllers\QuizController;

Route::middleware(['auth'])->group(function () {
    // —————— Ruta de debug temporal ——————
    Route::get('/debug-habits', function() {
        $suggestions = \App\Models\HabitSuggestion::all();
        $popularSuggestions = \App\Models\HabitSuggestion::popular(6);
        
        return response()->json([
            'total_count' => $suggestions->count(),
            'popular_count' => $popularSuggestions->count(),
            'all_suggestions' => $suggestions->pluck('name'),
            'popular_suggestions' => $popularSuggestions->pluck('name')
        ]);
    });

    // —————— Componente Interactivo de Hábitos ——————
    Route::get('/habits', [HabitController::class, 'index'])
        ->name('habits.index');
    Route::get('/habits/interactive', function () {
        return view('habits.interactive');
    })->name('habits.interactive');
    Route::get('/habits/data', [HabitController::class, 'getData'])
        ->name('habits.data');
    Route::post('/habits/suggestions/{suggestion}/add', [HabitController::class, 'addSuggested'])
        ->name('habits.add-suggested');

    // —————— Formulario de Hábito ——————
    Route::get('/formulario-habito', [FormularioHabitoController::class, 'show'])
        ->name('formulario_habito.show');
    Route::post('/formulario-habito', [FormularioHabitoController::class, 'store'])
        ->name('formulario_habito.store');

    // —————— Cuestionario de Preguntas ——————
    Route::get('/preguntas-form', [PreguntasFormController::class, 'show'])
        ->name('preguntas_form.show');
    Route::post('/preguntas-form', [PreguntasFormController::class, 'store'])
        ->name('preguntas_form.store');

    // —————— API de Hábitos ——————
    Route::post('/habits', [HabitController::class, 'store'])
        ->name('habits.store');
    Route::post('/habits/{habit}/complete', [HabitController::class, 'complete'])
        ->name('habits.complete');
    Route::post('/habits/{habit}/undo', [HabitController::class, 'undo'])
        ->name('habits.undo');
    Route::put('/habits/{habit}', [HabitController::class, 'update'])
        ->name('habits.update');
    Route::delete('/habits/{habit}', [HabitController::class, 'destroy'])
        ->name('habits.destroy');
    Route::post('/habits/create-from-suggestion', [HabitController::class, 'createFromSuggestion'])
        ->name('habits.createFromSuggestion');
    Route::get('/api/user-habits', [HabitController::class, 'getUserHabits'])
        ->name('api.userHabits');
    Route::get('/api/suggestions', [HabitController::class, 'getSuggestions'])
        ->name('api.suggestions');

    // —————— Quiz de Hábitos ——————
    Route::get('/quiz', [QuizController::class, 'show'])
        ->name('quiz.show');
    Route::post('/quiz/complete', [QuizController::class, 'complete'])
        ->name('quiz.complete');
});
// Página principal - Dashboard (requiere autenticación)
Route::get('/', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Página de bienvenida para usuarios no autenticados
Route::get('/welcome', function () {
    return view('welcome');
})->name('welcome');

// Rutas de registro
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Ruta de dashboard alternativa (redirige a la raíz)
Route::get('/dashboard', function () {
    return redirect('/');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Comentadas las rutas de Google ya que el registro es sin Google
/*
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
*/

require __DIR__.'/auth.php';
