<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
  ->withRouting(
    web: __DIR__ . '/../routes/web.php',
    api: __DIR__ . '/../routes/api.php',
    commands: __DIR__ . '/../routes/console.php',
    health: '/up',
  )
  ->withMiddleware(function (Middleware $middleware) {
    $middleware->api(prepend: [
      \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
    ]);

    $middleware->alias([
      'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,

      //spatie permission
      'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
      'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
      'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
    ]);

    //
  })
  ->withExceptions(function (Exceptions $exceptions) {
    $exceptions->render(function (\Illuminate\Database\Eloquent\ModelNotFoundException $e, $request) {
      if ($request->is('api/*')) {
        return response()->json([
          'message' => 'Resource not found'
        ], 404);
      }
    });

    $exceptions->render(function (Throwable $e, Request $request) {
      if ($request->is('api/*') || $request->wantsJson()) {
        if ($e instanceof ValidationException) {
          return response()->json([
            'status'  => 'error',
            'message' => 'Validation failed, check again your input.',
            'errors'  => $e->errors(),
          ], 422);
        }

        // Handling Http Exceptions (404 Not Found, 403 Forbidden, dll)
        if ($e instanceof HttpExceptionInterface) {
          $statusCode = $e->getStatusCode();

          return response()->json([
            'status'  => 'error',
            'message' => $e->getMessage() ?: 'Something went wrong with HTTP request.',
          ], $statusCode);
        }

        // Handling General/Server Exceptions (Error 500)
        return response()->json([
          'status'  => 'error',
          'message' => config('app.debug')
            ? $e->getMessage()
            : 'Something went wrong in internal server.',
        ], 500);
      }
    });
  })->create();
