<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Mockery\Exception\BadMethodCallException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($request->wantsJson()) {
            if ($exception instanceof ValidationException) {
                $errors = $exception->errors();
                foreach ($errors as $key => $error) {
                    $myerrors[$key] = $error[0];
                }
                return api_response(false, $error[0], $myerrors, 422);
            } elseif ($exception instanceof NotFoundHttpException) {
                return api_response(false, 'This url not found please check it again', null, 404);
            } elseif ($exception instanceof MethodNotAllowedHttpException) {
                return api_response(false, $exception->getMessage(), null, 405);
            } elseif ($exception instanceof BadMethodCallException) {
                return api_response(false, $exception->getMessage(), null, 500);
            } elseif ($exception instanceof AuthenticationException) {
                return api_response(false, __("You must be loginned"), null, 401);
            } elseif ($exception instanceof ModelNotFoundException) {
                return api_response(false, $exception->getMessage(), null, 404);
            }
        }
        return parent::render($request, $exception);
    }
}
