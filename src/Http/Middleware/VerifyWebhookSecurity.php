<?php

namespace Noardcode\LaravelSignhost\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Noardcode\LaravelSignhost\Facades\SignhostClient;

/**
 * Middleware VerifyWebhookSecurity
 *
 * Validates incoming Signhost webhook requests by checking the Authorization
 * header. Allows the request to proceed only when the validation succeeds.
 *
 * @middleware VerifyWebhookSecurity
 */
class VerifyWebhookSecurity
{
    /**
     * Handle an incoming webhook request.
     *
     * Ensures the Authorization header matches the configured token
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        if (! $request->headers->has('Authorization')) {
            return response()->json(['success' => true, 'message' => 'Authorization header is missing.'], 200);
        }

        if ($request->header('Authorization') !== SignhostClient::getClient()->getWebhookToken()) {
            return response()->json(['success' => false, 'message' => 'Authorization header is incorrect.'], 200);
        }

        return $next($request);
    }
}
