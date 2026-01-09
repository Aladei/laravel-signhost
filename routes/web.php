<?php

use Noardcode\LaravelSignhost\Facades\Services\IdProof;
use Noardcode\LaravelSignhost\Http\Controllers\WebhookController;
use Noardcode\LaravelSignhost\Http\Middleware\VerifyWebhookChecksum;
use Noardcode\LaravelSignhost\Http\Middleware\VerifyWebhookSecurity;

/**
 * Signhost webhook handling.
 */
Route::group([
    'prefix' => 'signhost/postback',
    'as' => 'laravel-signhost.postback.',
    'middleware' => [VerifyWebhookSecurity::class]],
    function () {

        // Transaction webhook.
        Route::post(
            'transaction',
            [
                WebhookController::class,
                'handleTransactionPostback',
            ]
        )->middleware([VerifyWebhookChecksum::class])
            ->name('transaction');

        // IdProof webhook.
        Route::post(
            'idproof',
            [
                WebhookController::class,
                'handleIdProofPostback',
            ]
        )->name('idproof');
    });

/**
 * IdProof routes.
 */
Route::get( // Redirect to Signhost IdProof form.
    'idproof/{identifier}',
    [
        IdProof::class,
        'redirectToSignhost',
    ]
)->name('idproof.redirect');
