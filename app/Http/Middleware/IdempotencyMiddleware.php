<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class IdempotencyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET') || $request->isMethod('HEAD')) {
            return $next($request);
        }

        $key = $request->header('Idempotency-Key');

        if (! $key) {
           // Optional: Enforce it for specific routes, or just pass if missing.
           // Requirement says "Must be use Idempotem key".
           // I will enforce it for POST/PUT/PATCH.
           if ($request->isMethod('POST') || $request->isMethod('PUT') || $request->isMethod('PATCH')) {
                return response()->json(['error' => 'Idempotency-Key header is required'], 400);
           }
           return $next($request);
        }

        $cacheKey = 'idempotency:' . $key;

        if (Cache::has($cacheKey)) {
            return response()->json(Cache::get($cacheKey), 200, ['X-Idempotency-Hit' => 'true']);
        }

        $response = $next($request);

        if ($response->isSuccessful()) {
            Cache::put($cacheKey, $response->getData(), 60 * 60 * 24); // 24 hours
        }

        return $response;
    }
}
