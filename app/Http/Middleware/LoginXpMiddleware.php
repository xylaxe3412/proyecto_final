<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LoginXpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Verificar si ya recibió XP hoy
            if (!$user->last_login_xp || !$user->last_login_xp->isToday()) {
                $user->addXP(10, 'Login diario');
                $user->last_login_xp = now();
                $user->save();
                
                // Agregar mensaje de XP ganado a la sesión
                session()->flash('xp_gained', [
                    'amount' => 10,
                    'reason' => '¡Bienvenido de vuelta! XP por constancia'
                ]);
            }
        }

        return $next($request);
    }
}
