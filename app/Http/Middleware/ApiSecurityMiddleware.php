<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Log;

/**
 * ApiSecurityMiddleware
 * Middleware keamanan tambahan untuk API:
 * - Security headers (XSS, clickjacking, MIME sniff)
 * - Request body size limit
 * - Suspicious request detection (SQL injection, XSS patterns)
 * - Rate limiting logging
 */
class ApiSecurityMiddleware
{
    // Pola berbahaya yang sering digunakan dalam serangan
    private const DANGEROUS_PATTERNS = [
        '/(\bSELECT\b|\bUNION\b|\bDROP\b|\bDELETE\b|\bINSERT\b|\bUPDATE\b)\s+/i', // SQL Injection
        '/<script[\s\S]*?>[\s\S]*?<\/script>/i',  // XSS Script tag
        '/javascript:/i',                           // XSS javascript:
        '/on\w+\s*=/i',                             // XSS event handlers
        '/\.\.\//i',                                // Path traversal
        '/\x00/',                                   // Null byte injection
    ];

    public function handle(Request $request, Closure $next)
    {
        // ── 1. Cek ukuran request body (max 10MB) ──
        $contentLength = $request->header('Content-Length', 0);
        if ($contentLength > 10 * 1024 * 1024) {
            return response()->json([
                'success' => false,
                'message' => 'Request terlalu besar. Maksimal 10MB.'
            ], 413);
        }

        // ── 2. Deteksi pola berbahaya di input ──
        $dangerousInput = $this->detectDangerousInput($request);
        if ($dangerousInput) {
            Log::warning('Suspicious API request detected', [
                'ip'         => $request->ip(),
                'url'        => $request->fullUrl(),
                'method'     => $request->method(),
                'user_agent' => $request->userAgent(),
                'pattern'    => $dangerousInput,
                'user_id'    => auth()->id(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Request tidak valid.'
            ], 400);
        }

        // ── 3. Proses request ──
        $response = $next($request);

        // ── 4. Tambahkan security headers ke response ──
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'geolocation=(self), camera=(self), microphone=()');
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        $response->headers->set('Content-Security-Policy', "default-src 'self'; frame-ancestors 'none';");
        // Hapus header yang mengexpose stack teknologi
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        return $response;
    }

    /**
     * Periksa apakah ada input yang mengandung pola berbahaya
     */
    private function detectDangerousInput(Request $request): ?string
    {
        // Hanya cek untuk endpoint non-file (tidak cek binary upload)
        $contentType = $request->header('Content-Type', '');
        if (str_contains($contentType, 'multipart/form-data')) {
            // Untuk upload file, hanya cek field teks (bukan file)
            $inputs = $request->except(['foto_ktp', 'foto', 'foto_profil']);
        } else {
            $inputs = $request->all();
        }

        foreach ($inputs as $key => $value) {
            if (!is_string($value)) continue;

            foreach (self::DANGEROUS_PATTERNS as $pattern) {
                if (preg_match($pattern, $value)) {
                    return "Pattern ditemukan di field '{$key}'";
                }
            }
        }

        return null;
    }
}
