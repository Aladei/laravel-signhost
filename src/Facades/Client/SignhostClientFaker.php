<?php

namespace Noardcode\LaravelSignhost\Facades\Client;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Noardcode\LaravelSignhost\Enums\TransactionStatus;
use Noardcode\LaravelSignhost\Models\Transaction as TransactionModel;

/**
 * Trait SignhostClientFaker
 *
 * Provides HTTP fakes for SignhostClient interactions to facilitate testing
 * without hitting the real Signhost API. Stubs transaction endpoints, including
 * file uploads and transaction lifecycle actions.
 *
 * @testing SignhostClientFaker
 */
trait SignhostClientFaker
{
    const EXTERNAL_TRANSACTION_ID = 'dcb9f104-42d4-48c7-9b0f-46b1129d862c';

    public static function fake(): void
    {
        Http::fake([
            // Only fake requests to the Signhost API domain
            '*/transaction/*/start' => function (Request $request) {
                return Http::response(self::getDummyTransactionResponse());
            },
            '*/transaction/*/file/*' => function (Request $request) {
                // Try to get real file if not in CI
                $realFile = self::getRealFileContent($request);
                if ($realFile !== null) {
                    return Http::response($realFile, 201, ['Content-Type' => 'application/pdf']);
                }

                // Fallback to dummy PDF
                $pdf = self::getDummyPdfContent();

                return Http::response($pdf, 201, ['Content-Type' => 'application/pdf']);
            },
            '*/file/receipt/*' => function (Request $request) {
                // Try to get real receipt if not in CI
                $realReceipt = self::getRealReceiptContent($request);
                if ($realReceipt !== null) {
                    return Http::response($realReceipt, 200, ['Content-Type' => 'application/pdf']);
                }

                // Fallback to dummy PDF
                $pdf = self::getDummyPdfContent();

                return Http::response($pdf, 200, ['Content-Type' => 'application/pdf']);
            },
            '*/transaction/*' => function (Request $request) {
                switch ($request->method()) {
                    case 'GET':
                        $request = $request->withData($request->data() + ['Id' => self::getUuidParameter($request)]);

                        return Http::response(self::getDummyTransactionResponse($request));
                    case 'DELETE':
                        return Http::response(self::getDummyTransactionResponse(
                            $request,
                            TransactionStatus::Cancelled
                        ));
                    default:
                        return Http::response([], 404);
                }
            },
            'https://api.signhost.com/*' => function (Request $request) {
                if ($request->method() === 'GET') {
                    $request = $request->withData($request->data() + ['Id' => self::getUuidParameter($request)]);
                }

                return Http::response(self::getDummyTransactionResponse($request));
            },
        ]);
        Http::preventStrayRequests(false); // Allow other requests to go through
    }

    protected static function getDummyTransactionResponse(
        ?Request $request = null,
        ?TransactionStatus $status = null
    ): array {
        $requestData = $request?->data() ?? [];
        $signersResponse = [];
        $receiversResponse = [];

        // Try to get real transaction data if Id is provided
        if (! empty($requestData['Id'])) {
            $transaction = TransactionModel::find($requestData['Id']);
            if ($transaction) {
                // Use receivers as signers and fake Receivers array
                foreach ($transaction->receivers as $receiver) {
                    $signersResponse[] = [
                        'Id' => $receiver->id,
                        'Email' => $receiver->email ?? null,
                        'SignUrl' => 'https://view.signhost.com/sign/'.$transaction->id,
                        'ShowUrl' => 'https://view.signhost.com/show/document/'.$transaction->id,
                        'ReceiptUrl' => 'https://view.signhost.com/show/receipt/'.$transaction->id,
                        'CreatedDateTime' => $transaction->created_at?->toIso8601String() ?? now()->toIso8601String(),
                        'ModifiedDateTime' => $transaction->updated_at?->toIso8601String() ?? now()->toIso8601String(),
                        'Activities' => $receiver->activities?->map(fn ($activity) => [
                            'Id' => $activity->id,
                            'Code' => $activity->state_code,
                            'Activity' => $activity->state ?? '',
                            'CreatedDateTime' => $activity->created_at?->toIso8601String() ?? now()->toIso8601String(),
                        ]) ?? [],
                    ];
                    $receiversResponse[] = [
                        'Name' => $receiver->name ?? 'Receiver '.($receiver->id ?? ''),
                        'Email' => $receiver->email ?? 'receiver'.($receiver->id ?? '').'@example.com',
                        'Language' => $receiver->language ?? 'nl-NL',
                        'Subject' => $receiver->subject ?? 'Default Subject',
                        'Message' => $receiver->message ?? 'This is a test message for receiver.',
                        'Reference' => $receiver->reference ?? 'REF'.($receiver->id ?? ''),
                        'Context' => $receiver->context ?? (object) [],
                    ];
                }

                return [
                    'Id' => $transaction->id,
                    'Status' => $status?->value ?? ($transaction->status_code->value ?? TransactionStatus::WaitingForDocument->value),
                    'Files' => [],
                    'Seal' => $transaction->seal ?? false,
                    'Signers' => $signersResponse,
                    'Receivers' => $receiversResponse,
                    'Reference' => $transaction->reference ?? null,
                    'PostbackUrl' => $transaction->postback_url ?? null,
                    'SignRequestMode' => $transaction->sign_request_mode ?? 0,
                    'DaysToExpire' => $transaction->days_to_expire ?? 60,
                    'SendEmailNotifications' => $transaction->send_email_notifications ?? false,
                    'Language' => $transaction->language ?? 'en-US',
                    'CreatedDateTime' => $transaction->created_at?->toIso8601String() ?? now()->toIso8601String(),
                    'ModifiedDateTime' => $transaction->updated_at?->toIso8601String() ?? now()->toIso8601String(),
                    'CanceledDateTime' => $transaction->canceled_at?->toIso8601String() ?? null,
                    'Context' => $transaction->context ?? null,
                ];
            }
        }

        // Fallback to dummy response
        if (! empty($requestData['Signers'])) {
            foreach ($requestData['Signers'] as $signer) {
                $signersResponse[] = array_merge([
                    'Id' => (string) Str::uuid(),
                    'Expires' => null,
                    'SignUrl' => 'https://view.signhost.com/sign/',
                    'ShowUrl' => 'https://view.signhost.com/show/document/null?signerId=null',
                    'ReceiptUrl' => 'https://view.signhost.com/show/receipt/null?signerId=null',
                    'CreatedDateTime' => now()->toIso8601String(),
                    'ModifiedDateTime' => now()->toIso8601String(),
                ], $signer);
            }
        }

        return [
            'Id' => $requestData['Id'] ?? (string) Str::uuid(),
            'Status' => $status?->value ?? TransactionStatus::WaitingForDocument->value,
            'Files' => [],
            'Seal' => $requestData['Seal'] ?? false,
            'Signers' => $signersResponse,
            'Receivers' => $requestData['Receivers'] ?? [],
            'Reference' => $requestData['Reference'] ?? null,
            'PostbackUrl' => $requestData['PostbackUrl'] ?? null,
            'SignRequestMode' => $requestData['SignRequestMode'] ?? 0,
            'DaysToExpire' => $requestData['DaysToExpire'] ?? 60,
            'SendEmailNotifications' => $requestData['SendEmailNotifications'] ?? false,
            'Language' => $requestData['Language'] ?? 'en-US',
            'CreatedDateTime' => now()->toIso8601String(),
            'ModifiedDateTime' => now()->toIso8601String(),
            'CanceledDateTime' => null,
            'Context' => $requestData['Context'] ?? null,
        ];
    }

    protected static function getUuidParameter(?Request $request = null): string
    {
        if (! $request) {
            return self::EXTERNAL_TRANSACTION_ID;
        }

        if (
            preg_match('/transaction\/([0-9a-fA-F\-]{36})/', $request->url(), $matches)
            && Str::isUuid($matches[1])
        ) {
            return $matches[1];
        }

        return self::EXTERNAL_TRANSACTION_ID;
    }

    protected static function getDummyPdfContent(): string
    {
        // Minimal valid PDF binary
        return "%PDF-1.4\n1 0 obj\n<<>>\nendobj\n2 0 obj\n<<>>\nendobj\ntrailer\n<<>>\n%%EOF";
    }

    protected static function getRealFileContent(Request $request): ?string
    {
        // Only return real files when not in CI environment
        if (app()->environment('testing')) {
            return null;
        }

        // Return dummy PDF from test assets
        $dummyPath = __DIR__.'/../../../tests/Assets/dummy.pdf';

        if (file_exists($dummyPath)) {
            return file_get_contents($dummyPath);
        }

        return null;
    }

    protected static function getRealReceiptContent(Request $request): ?string
    {
        // Only return real receipts when not in CI environment
        if (app()->environment('testing')) {
            return null;
        }

        // Return dummy receipt PDF from test assets
        $dummyPath = __DIR__.'/../../../tests/Assets/dummy-receipt.pdf';
        if (file_exists($dummyPath)) {
            return file_get_contents($dummyPath);
        }

        return null;
    }
}
