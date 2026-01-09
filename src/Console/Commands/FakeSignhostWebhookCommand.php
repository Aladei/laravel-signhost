<?php

namespace Noardcode\LaravelSignhost\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Noardcode\LaravelSignhost\Enums\SignerActivityStatus;
use Noardcode\LaravelSignhost\Enums\TransactionStatus;
use Noardcode\LaravelSignhost\Facades\SignhostClient;
use Noardcode\LaravelSignhost\Models\Transaction;

class FakeSignhostWebhookCommand extends Command
{
    protected $signature = 'signhost:fake-webhook {transactionId}';

    protected $description = 'Fake a Signhost webhook call for a transaction, using database data and filling missing fields with fake data.';

    /**
     * @throws ConnectionException
     */
    public function handle(): int
    {
        $transactionId = $this->argument('transactionId');
        $transaction = Transaction::with(['receivers', 'activities'])->find($transactionId);

        if (! $transaction) {
            $this->error('Transaction not found.');

            return 1;
        }

        // Build the webhook payload structure
        $payload = [
            'Id' => $transaction->id,
            'Status' => $transaction->status_code->value ?? TransactionStatus::WaitingForDocument->value,
            'Seal' => $transaction->seal ?? false,
            'Language' => $transaction->language ?? 'en-US',
            'Reference' => $transaction->reference ?? Str::random(8),
            'PostbackUrl' => $transaction->postback_url ?? 'https://example.com/webhook',
            'SignRequestMode' => $transaction->sign_request_mode ?? 1,
            'DaysToExpire' => $transaction->days_to_expire ?? 60,
            'SendEmailNotifications' => $transaction->send_email_notifications ?? false,
            'CreatedDateTime' => $transaction->created_at?->toIso8601String() ?? now()->toIso8601String(),
            'ModifiedDateTime' => $transaction->updated_at?->toIso8601String() ?? now()->toIso8601String(),
            'CanceledDateTime' => $transaction->canceled_at?->toIso8601String() ?? null,
            'Context' => $transaction->context ?? [],
            'Signers' => [],
            'Receivers' => [],
        ];

        // Ask user to choose activity status from enum
        $statusChoices = array_map(fn ($case) => $case->label(), SignerActivityStatus::cases());
        $chosenStatus = $this->choice('Choose a signer activity status', $statusChoices, $statusChoices[0]);
        $chosenEnum = null;
        foreach (SignerActivityStatus::cases() as $case) {
            if ($case->label() === $chosenStatus) {
                $chosenEnum = $case;
                break;
            }
        }

        foreach ($transaction->receivers as $receiver) {
            $activities = $transaction->activities->where('transaction_signer_id', $receiver->id)->map(function ($activity) {
                return [
                    'Id' => $activity->id,
                    'Code' => $activity->state_code,
                    'Activity' => $activity->state,
                    'CreatedDateTime' => $activity->created_at?->toIso8601String() ?? now()->toIso8601String(),
                ];
            })->values()->toArray();

            // If no real activities, generate fake one with chosen status
            if ($chosenEnum) {
                $activities[] = [
                    'Id' => (string) Str::uuid(),
                    'Code' => $chosenEnum->value,
                    'Activity' => $chosenEnum->label(),
                    'CreatedDateTime' => now()->toIso8601String(),
                ];
            }

            $payload['Signers'][] = [
                'Id' => $receiver->external_signer_id ?? $receiver->id,
                'Email' => $receiver->email ?? 'fake@example.com',
                'Mobile' => $receiver->mobile ?? null,
                'Iban' => $receiver->iban ?? null,
                'BSN' => $receiver->bsn ?? null,
                'RequireScribbleName' => $receiver->require_scribble_name ?? false,
                'RequireScribble' => $receiver->require_scribble ?? false,
                'RequireEmailVerification' => $receiver->require_email_verification ?? false,
                'RequireSmsVerification' => $receiver->require_sms_verification ?? false,
                'RequireIdealVerification' => $receiver->require_ideal_verification ?? false,
                'RequireDigidVerification' => $receiver->require_digid_verification ?? false,
                'RequireSurfnetVerification' => $receiver->require_surfnet_verification ?? false,
                'SendSignRequest' => $receiver->send_sign_request ?? false,
                'SendSignConfirmation' => $receiver->send_sign_confirmation ?? false,
                'SignRequestMessage' => $receiver->sign_request_message ?? '',
                'DaysToRemind' => $receiver->days_to_remind ?? 7,
                'Language' => $receiver->language ?? 'en-US',
                'ScribbleName' => $receiver->scribble_name ?? '',
                'ScribbleNameFixed' => $receiver->scribble_name_fixed ?? false,
                'Reference' => $receiver->reference ?? '',
                'IntroText' => $receiver->intro_text ?? '',
                'ReturnUrl' => $receiver->return_url ?? '',
                'AllowDelegation' => $receiver->allow_delegation ?? false,
                'Activities' => $activities,
                'RejectReason' => $receiver->reject_reason ?? '',
                'DelegateReason' => $receiver->delegate_reason ?? '',
                'DelegateSignerEmail' => $receiver->delegate_signer_email ?? '',
                'DelegateSignerName' => $receiver->delegate_signer_name ?? '',
                'DelegateSignUrl' => $receiver->delegate_sign_url ?? '',
                'SignUrl' => 'https://view.signhost.com/sign/'.$transaction->id,
                'SignedDateTime' => $receiver->signed_date_time ?? null,
                'RejectDateTime' => $receiver->reject_date_time ?? null,
                'CreatedDateTime' => $receiver->created_at?->toIso8601String() ?? now()->toIso8601String(),
                'ModifiedDateTime' => $receiver->updated_at?->toIso8601String() ?? now()->toIso8601String(),
                'Context' => $receiver->context ?? [],
            ];
        }

        // Add receivers (if you want to include them)
        foreach ($transaction->receivers as $receiver) {
            $payload['Receivers'][] = [
                'Id' => $receiver->id,
                'Name' => $receiver->name ?? '',
                'Email' => $receiver->email ?? 'fake@example.com',
                'Language' => $receiver->language ?? 'en-US',
                'Message' => $receiver->message ?? '',
                'Reference' => $receiver->reference ?? '',
                'Context' => $receiver->context ?? [],
                'CreatedDateTime' => $receiver->created_at?->toIso8601String() ?? now()->toIso8601String(),
                'ModifiedDateTime' => $receiver->updated_at?->toIso8601String() ?? now()->toIso8601String(),
                'Activities' => [],
            ];
        }

        $payload['Checksum'] = SignhostClient::getClient()->createWebhookChecksum(
            transactionId: $transaction->id,
            status: $payload['Status']
        );

        $webhookUrl = route('laravel-signhost.postback.transaction');
        if (config('signhost.simulation.webhooks.transaction')) {
            $webhookUrl = config('signhost.simulation.webhooks.transaction');
        }

        $authorization = config('signhost.webhook.token');

        $headers = $authorization ? ['Authorization' => $authorization] : [];
        $response = Http::withHeaders($headers)
            ->post($webhookUrl, $payload);

        $this->info('Fake Signhost webhook sent!');
        $this->table(['Field', 'Value'], [
            ['Transaction ID', $transactionId],
            ['Status', $chosenEnum->label() ?? 'Unknown'],
            ['Endpoint', $webhookUrl],
            ['Json', json_encode($response->json())],
            ['Response', $response->status()],
        ]);

        return 0;
    }
}
