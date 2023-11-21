<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        $this->renderable(function (Throwable $e, $request) {

            if ($e instanceof ValidationException) {
                $currentRoute = request()->route()->uri;
                if($currentRoute != 'admin/auth/login') {
                    return response()->json([
                        'success' => false,
                        'message' => config('messages.common_validation_error') ?? 'Validation error.',
                        'data'    => $e->errors()
                    ], $e->status);
                }

            }

            if ($request->is('api/*')) {
                if ($e instanceof AccessDeniedHttpException) {
                    return response()->json([
                        'code' => 401,
                        'success' => false,
                        'message' => config('messages.unauthorized_token_error') ??
                            'You are not authorized to use this feature.',
                        'requested_by' => [
                            'role' => request()->user()->user_type ?? ''
                        ]
                    ],401);
                }

                $response = [
                    'code' => 400,
                    'success' => false,
                    "message" => config('messages.api_common_error') ?? 'Ops! Something went wrong!.',
                    'requested_by' => [
                        'role' => request()->user()->user_type ?? ''
                    ]
                ];
                return response()->json($response, 400);
            }
        });
    }
}
