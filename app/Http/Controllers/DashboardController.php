<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Habit;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $habitos = Habit::where('user_id', $user->id)->get();
        return view('dashboard', compact('user', 'habitos'));
    }
}