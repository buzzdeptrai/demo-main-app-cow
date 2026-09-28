<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private const DISCORD_SKIP_CODES = [401];

    public function render($request, Throwable $exception)
    {
        if (!$request->expectsJson() && !$request->is('api/*')) {
            return parent::render($request, $exception);
        }

        if ($exception instanceof ValidationException) {
            $response = response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $exception->errors(),
            ], 422);
            $this->notifyDiscord($request, $response);
            return $response;
        }

        if ($exception instanceof ModelNotFoundException) {
            $response = response()->json([
                'success' => false,
                'message' => 'Resource not found',
            ], 404);
            $this->notifyDiscord($request, $response);
            return $response;
        }

        if ($exception instanceof AuthenticationException) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        if ($exception instanceof AuthorizationException) {
            $response = response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
            $this->notifyDiscord($request, $response);
            return $response;
        }

        // Fallback for unhandled exceptions
        $code = method_exists($exception, 'getStatusCode')
            ? $exception->getStatusCode()
            : 500;

        $response = response()->json([
            'success' => false,
            'message' => app()->environment('production')
                ? 'Internal server error'
                : $exception->getMessage(),
        ], $code);
        $this->notifyDiscord($request, $response);
        return $response;
    }

    private function notifyDiscord($request, $response): void
    {
        try {
            $status = $response->getStatusCode();

            if (in_array($status, self::DISCORD_SKIP_CODES)) {
                return;
            }

            $webhookUrl = config('services.discord.webhook_url');
            if (!$webhookUrl) {
                return;
            }

            $body = json_decode($response->getContent(), true) ?? [];
            $errorMessage = $body['message'] ?? 'Unknown error';
            $emoji = $status >= 500 ? '🔥' : '⚠️';
            $method = $request->getMethod();
            $path = $request->getPathInfo();

            $embeds = [
                [
                    'title' => '📍 URL',
                    'description' => "`{$method}` " . $request->fullUrl(),
                    'color' => $status >= 500 ? 15158332 : 16753920,
                ],
                [
                    'title' => "❌ Response [{$status}]",
                    'description' => $errorMessage . $this->formatErrors($body),
                    'color' => 15158332,
                ],
            ];

            $requestBody = $request->all();
            if (!empty($requestBody)) {
                array_splice($embeds, 1, 0, [[
                    'title' => '📦 Request Body',
                    'description' => $this->codeBlock(json_encode($requestBody, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)),
                    'color' => 3447003,
                ]]);
            }

            $client = new \GuzzleHttp\Client(['timeout' => 3]);
            $client->post($webhookUrl, [
                'json' => [
                    'content' => "{$emoji} **API Error [{$status}]** — `{$method} {$path}` — IP: `{$request->ip()}`",
                    'embeds' => $embeds,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('Discord notify failed: ' . $e->getMessage());
        }
    }

    private function formatErrors(array $body): string
    {
        if (empty($body['errors'])) {
            return '';
        }
        return "\n\n**Validation Errors:**\n" . $this->codeBlock(
            json_encode($body['errors'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)
        );
    }

    private function codeBlock(string $content): string
    {
        if (strlen($content) > 900) {
            $content = substr($content, 0, 900) . "\n... [truncated]";
        }
        return "```json\n{$content}\n```";
    }
}
