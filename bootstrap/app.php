<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AuthCommonMiddleware;
use App\Http\Middleware\SchoolMiddleware;
use App\Http\Middleware\AdminMiddleware;

use App\Http\Middleware\StudentMiddleware;
use App\Http\Middleware\TeacherMiddleware;
use App\Http\Middleware\ParentMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'common' => AuthCommonMiddleware::class,
            'admin' => AdminMiddleware::class,
            'school' => SchoolMiddleware::class,
            'student' => StudentMiddleware::class,
            'teacher' => TeacherMiddleware::class,
            'parent' => ParentMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
