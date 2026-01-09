<?php

namespace Noardcode\LaravelSignhost\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Noardcode\LaravelSignhost\Events\SignhostIdProofReceived;
use Noardcode\LaravelSignhost\Events\SignhostTransactionActivity;
use Noardcode\LaravelSignhost\Handlers\HandleIdProofWebhook;
use Noardcode\LaravelSignhost\Handlers\HandleSignTransactionWebhook;
use Throwable;

/**
 * Controller WebhookController
 *
 * Receives and dispatches incoming Signhost webhook requests for
 * transactions and IdProof flows. Performs minimal orchestration
 * and hands off processing to dedicated handlers.
 *
 * @controller WebhookController
 */
class WebhookController
{
    /**
     * Handle Sign Transaction webhooks from Signhost.
     *
     * Dispatches a SignhostTransactionActivity event and invokes the
     * transaction handler with the raw JSON body.
     *
     * @param  Request  $request
     * @param  HandleSignTransactionWebhook  $handler
     * @return JsonResponse
     *
     * @throws Throwable
     */
    public function handleTransactionPostback(Request $request, HandleSignTransactionWebhook $handler): JsonResponse
    {
        SignhostTransactionActivity::dispatch($request);
        Log::info('Signhost transaction webhook received: '.$request->getContent());

        try {
            $jsonResponse = $request->getContent();
            ($handler)($jsonResponse);
        } catch (Throwable $e) {
            Log::error('Error processing Signhost transaction webhook: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error processing Signhost transaction webhook: '.$e->getMessage(),
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'Sign Transaction webhook received.',
        ], 200);
    }

    /**
     * Handle ID proof webhooks from Signhost.
     *
     * Dispatches a SignhostIdProofReceived event and invokes the
     * IdProof handler with the raw JSON body.
     *
     * @param  Request  $request
     * @param  HandleIdProofWebhook  $handler
     * @return JsonResponse
     *
     * @throws Throwable
     */
    public function handleIdProofPostback(
        Request $request,
        HandleIdProofWebhook $handler
    ) {
        SignhostIdProofReceived::dispatch($request);

        try {
            $jsonResponse = $request->getContent();
            ($handler)($jsonResponse);
        } catch (Throwable $e) {
            Log::error('Error processing Signhost transaction webhook: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Missing required data.',
            ], 200);
        }

        return response()->json([
            'success' => true,
            'message' => 'ID proof webhook received.',
        ]);
    }
}
