<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class BlockBots
{
    /**
     * User-Agent fragments thường gặp của bot/scanner (lowercase).
     */
    private const BAD_USER_AGENTS = [
        'curl',
        'wget',
        'python-requests',
        'python-urllib',
        'go-http-client',
        'java/',
        'okhttp',
        'libwww-perl',
        'httpclient',
        'nikto',
        'sqlmap',
        'nmap',
        'masscan',
        'zgrab',
        'nuclei',
        'dirbuster',
        'gobuster',
        'semrushbot',
        'ahrefsbot',
        'mj12bot',
        'dotbot',
        'scrapy',
        'headlesschrome',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if (!config('security.block_bots')) {
            return $next($request);
        }

        // Lớp 1 — Shared header gate.
        $expectedKey = config('security.app_key');
        if ($expectedKey && !hash_equals($expectedKey, (string) $request->header('X-App-Key'))) {
            return $this->deny('Invalid or missing application key');
        }

        // Lớp 2 — Chặn User-Agent rỗng hoặc thuộc blacklist.
        $userAgent = strtolower((string) $request->userAgent());
        if ($userAgent === '') {
            return $this->deny('Missing user agent');
        }

        foreach (self::BAD_USER_AGENTS as $bad) {
            if (str_contains($userAgent, $bad)) {
                return $this->deny('Automated client is not allowed');
            }
        }

        return $next($request);
    }

    private function deny(string $message): Response
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 403);
    }
}
