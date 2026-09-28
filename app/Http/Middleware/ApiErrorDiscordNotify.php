<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class ApiErrorDiscordNotify
{
    private const SKIP_CODES = [401, 404];

    private const MIN_ERROR_CODE = 400;

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $status = $response->getStatusCode();

        if ($status >= self::MIN_ERROR_CODE && !in_array($status, self::SKIP_CODES)) {
            $this->sendToDiscord($request, $response, $status);
        }

        return $response;
    }

    private function sendToDiscord(Request $request, Response $response, int $status): void
    {
        try {
            $webhookUrl = config('services.discord.webhook_url');

            if (!$webhookUrl) {
                return;
            }

            $responseBody = json_decode($response->getContent(), true) ?? [];
            $errorMessage = $responseBody['message'] ?? $responseBody['error'] ?? 'Unknown error';

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
                    'title' => '📦 Request Body',
                    'description' => $this->codeBlock(json_encode($request->all(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT)),
                    'color' => 3447003,
                ],
                [
                    'title' => "❌ Response [{$status}]",
                    'description' => $errorMessage . $this->formatValidationErrors($responseBody),
                    'color' => 15158332,
                ],
            ];

            $client = new \GuzzleHttp\Client(['timeout' => 3]);
            $client->post($webhookUrl, [
                'json' => [
                    'content' => "{$emoji} **API Error [{$status}]** — `{$method} {$path}` — IP: `{$request->ip()}`",
                    'embeds' => $embeds,
                ],
            ]);
        } catch (\Throwable $e) {
            Log::error('ApiErrorDiscordNotify failed: ' . $e->getMessage());
        }
    }

    private function formatValidationErrors(array $body): string
    {
        if (empty($body['errors'])) {
            return '';
        }

        $errorsJson = json_encode($body['errors'], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        return "\n\n**Validation Errors:**\n" . $this->codeBlock($errorsJson);
    }

    private function codeBlock(string $content): string
    {
        if (strlen($content) > 900) {
            $content = substr($content, 0, 900) . "\n... [truncated]";
        }
        return "```json\n{$content}\n```";
    }
}
