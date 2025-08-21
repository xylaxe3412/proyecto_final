<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormularioHabitoController extends Controller
{
    public function show()
    {
        // Muestra el formulario inicial
        return view('formulario_habito');
    }

    public function store(Request $request)
    {
        // Valida los campos
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'habito'  => 'required|string|max:255',
            'estado'  => 'required|integer|min:1|max:10',
        ]);

        // Guarda en sesión para pasar a la siguiente vista
        $request->session()->put('habito_form', $validated);

        // Redirige al cuestionario
        return redirect()->route('preguntas_form.show');
    }
}