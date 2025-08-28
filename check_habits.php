<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Hábitos de finanzas:\n";
$financeHabits = App\Models\HabitSuggestion::where('categoria', 'finanzas')->get();
echo "Total: " . $financeHabits->count() . "\n\n";

foreach($financeHabits as $habit) {
    echo "ID: " . $habit->id . " - " . $habit->name . "\n";
}

echo "\nTodas las categorías:\n";
$categories = App\Models\HabitSuggestion::select('categoria')->distinct()->pluck('categoria');
foreach($categories as $category) {
    $count = App\Models\HabitSuggestion::where('categoria', $category)->count();
    echo $category . ": " . $count . " hábitos\n";
}
