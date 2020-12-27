<?php

namespace App\Exceptions;

use App\Traits\AppResponder;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use AppResponder;

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Throwable $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable               $exception
     * @return \Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof HttpException) {
            $code    = $exception->getStatusCode();
            $message = Response::$statusTexts[$code];

            return $this->errorResponse($message, $code);
        }

        if ($exception instanceof ModelNotFoundException) {
            $model   = strtolower(class_basename($exception->getModel()));
            $message = "The {$model} with given ID has not been found";

            return $this->errorResponse($message, Response::HTTP_NOT_FOUND);
        }

        if ($exception instanceof AuthorizationException) {
            $message = $exception->getMessage();

            return $this->errorResponse($message, Response::HTTP_FORBIDDEN);
        }

        if ($exception instanceof AuthenticationException) {
            $message = $exception->getMessage();

            return $this->errorResponse($message, Response::HTTP_UNAUTHORIZED);
        }

        if ($exception instanceof ValidationException) {
            $message = $exception->validator->errors()->getMessages();

            return $this->errorResponse($message, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        if ($exception instanceof ClientException) {
            $originalException = json_decode($exception->getResponse()->getBody()->getContents());
            $originalMessage           = $originalException->error;
            $code              = $exception->getCode();

            return $this->errorResponse($originalMessage, $code);
        }

        if (env('APP_DEBUG')) {
            return $this->errorResponse('Unexpected error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return parent::render($request, $exception);
    }
}
