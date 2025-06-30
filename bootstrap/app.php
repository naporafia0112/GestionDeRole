<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\CheckRole;

$app = new Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

return $app->configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // 📌 Enregistrement des middlewares nommés
        $middleware->alias([
            'role' => CheckRole::class,
        ]);

        // Si besoin d'ajouter un middleware global : $middleware->append(...)
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Gestion des exceptions ici si tu veux
    })
    ->create();
