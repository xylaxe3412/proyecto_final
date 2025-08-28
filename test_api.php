<?php

require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Simular request para getSuggestions
$controller = new App\Http\Controllers\HabitController();
$response = $controller->getSuggestions();

echo "Respuesta de getSuggestions():\n";
$data = json_decode($response->getContent(), true);

echo "Categorías populares:\n";
foreach($data['popular'] as $habit) {
    echo "- " . $habit['name'] . " (" . $habit['categoria'] . ")\n";
}

echo "\nPor categoría:\n";
foreach($data['by_category'] as $category => $habits) {
    echo $category . " (" . count($habits) . " hábitos):\n";
    foreach($habits as $habit) {
        if (is_array($habit)) {
            echo "  - " . $habit['name'] . "\n";
        } else {
            echo "  - " . $habit->name . "\n";
        }
    }
}
