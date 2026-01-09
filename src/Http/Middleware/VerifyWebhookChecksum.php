<?php

namespace Noardcode\LaravelSignhost\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Noardcode\LaravelSignhost\Facades\SignhostClient;

/**
 * Middleware VerifyWebhookChecksum
 *
 * Validates incoming Signhost webhook requests by verifying the SHA1
 * checksum based on the configured webhook secret.
 * Allows the request to proceed only when the validation succeeds.
 *
 * @middleware VerifyWebhookChecksum
 */
class VerifyWebhookChecksum
{
    /**
     * Handle an incoming webhook request.
     *
     * Ensures the payload checksum matches the locally computed checksum.
     * Only available for digital signing withing Signhost, not for IdProof.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): mixed
    {
        $data = $request->all();
        if (empty($data['Id']) || empty($data['Checksum']) || empty($data['Status'])) {
            return response()->json(['success' => false, 'message' => 'Missing required data.'], 200);
        }

        $secret = SignhostClient::getClient()->getWebhookSecret();

        $localChecksum = sha1("{$data['Id']}||{$data['Status']}|{$secret}");

        if ($localChecksum !== $data['Checksum']) {
            return response()->json(['success' => false, 'message' => 'Checksums are not equal.'], 200);
        }

        return $next($request);
    }
}
