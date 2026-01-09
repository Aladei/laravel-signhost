<?php

namespace Noardcode\LaravelSignhost\Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Noardcode\LaravelSignhost\Enums\SignRequestMode;
use Noardcode\LaravelSignhost\Enums\TransactionStatus;
use Noardcode\LaravelSignhost\Events\SignhostIdProofReceived;
use Noardcode\LaravelSignhost\Events\SignhostTransactionActivity;
use Noardcode\LaravelSignhost\Events\SignhostTransactionFinalized;
use Noardcode\LaravelSignhost\Models\File;
use Noardcode\LaravelSignhost\Models\Transaction;
use Noardcode\LaravelSignhost\Models\Transaction\Receiver as TransactionReceiver;
use Noardcode\LaravelSignhost\Tests\TestCase;

class WebhookControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('signhost.webhook.token', 'test-token');
        config()->set('signhost.webhook.secret', 'test-secret');
    }

    public function test_transaction_postback_dispatches_event_and_updates_status(): void
    {
        Event::fake();

        // Seed a transaction that the handler can update.
        $txId = 'dcb9f104-42d4-48c7-9b0f-46b1129d862c';
        $transaction = Transaction::factory()->create(['id' => $txId, 'status' => TransactionStatus::InProgress->value]);

        $status = TransactionStatus::Signed->value;
        $checksum = sha1($txId.'||'.$status.'|'.config('signhost.webhook.secret'));

        $payload = [
            'Id' => $txId,
            'Status' => $status,
            'Checksum' => $checksum,
            // No Signers needed for this update path
        ];

        $response = $this->withHeaders([
            'Authorization' => 'test-token',
        ])->postJson(route('laravel-signhost.postback.transaction'), $payload);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Sign Transaction webhook received.');

        Event::assertDispatched(SignhostTransactionActivity::class);

        $this->assertDatabaseHas('sh_transactions', [
            'id' => $transaction->id,
            'status' => $status,
        ]);
    }

    public function test_idproof_postback_dispatches_event_and_persists_transaction(): void
    {
        Event::fake();

        $status = TransactionStatus::Signed->value;
        $txId = 'dcb9f104-42d4-48c7-9b0f-46b1129d862c';
        $checksum = sha1($txId.'||'.$status.'|'.config('signhost.webhook.secret'));

        $payload = [
            'Id' => $txId,
            'Files' => [],
            'Language' => 'en-US',
            'Seal' => false,
            'Signers' => [],
            'Receivers' => [],
            'Reference' => '1234567890',
            'PostbackUrl' => 'https://example.com/webhook/idproof',
            'SignRequestMode' => SignRequestMode::AtOnce->value,
            'DaysToExpire' => 60,
            'SendEmailNotifications' => false,
            'Status' => $status,
            'CancelationReason' => null,
            'Context' => ['findings' => ['authenticated' => true, 'probability' => 100]],
            'CreatedDateTime' => '2025-10-14T12:00:00+00:00',
            'ModifiedDateTime' => '2025-10-14T12:00:00+00:00',
            'CanceledDateTime' => null,
            'Checksum' => $checksum,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'test-token',
        ])->postJson(route('laravel-signhost.postback.idproof'), $payload);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'ID proof webhook received.');

        Event::assertDispatched(SignhostIdProofReceived::class);

        $this->assertDatabaseHas('sh_transactions', [
            'id' => $txId,
            'status' => $status,
            'type' => 'id_proof',
        ]);
    }

    public function test_transaction_postback_with_signers_and_activities_persists_and_finalizes(): void
    {
        Event::fake();

        // Seed a transaction and matching receiver so activities can be linked.
        $txId = 'dcb9f104-42d4-48c7-9b0f-46b1129d862c';
        $transaction = Transaction::factory()->create(['id' => $txId, 'status' => TransactionStatus::InProgress->value]);
        $receiver = TransactionReceiver::factory()->make([
            'transaction_id' => $transaction->id,
            'email' => 'john@example.com',
            'name' => 'John',
            'language' => 'en-US',
        ]);
        $receiver->id = 'signer-1';
        $receiver->save();

        $status = TransactionStatus::Signed->value;
        $checksum = sha1($txId.'||'.$status.'|'.config('signhost.webhook.secret'));

        $payload = [
            'Id' => $transaction->id,
            'Status' => $status,
            'Checksum' => $checksum,
            'Signers' => [
                [
                    'Id' => 'signer-1',
                    'Email' => 'john@example.com',
                    'Activities' => [
                        [
                            'Id' => 'act-1',
                            'Code' => 101,
                            'Activity' => 'Invitation Sent',
                            'CreatedDateTime' => '2025-10-14T12:00:00+00:00',
                        ],
                        [
                            'Id' => 'act-2',
                            'Code' => 203,
                            'Activity' => 'Signed',
                            'CreatedDateTime' => '2025-10-14T12:05:00+00:00',
                        ],
                    ],
                ],
            ],
        ];

        $response = $this->withHeaders([
            'Authorization' => 'test-token',
        ])->postJson(route('laravel-signhost.postback.transaction'), $payload);

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        // Status should be updated
        $this->assertDatabaseHas('sh_transactions', [
            'id' => $txId,
            'status' => $status,
        ]);

        // Activities should be persisted (mapper + handler path)
        $this->assertDatabaseHas('sh_transaction_activities', [
            'id' => 'act-1',
            'transaction_id' => $txId,
            'state_code' => 101,
        ]);
        $this->assertDatabaseHas('sh_transaction_activities', [
            'id' => 'act-2',
            'transaction_id' => $txId,
            'state_code' => 203,
        ]);

        // Finalized event should be dispatched when status == Signed
        Event::assertDispatched(SignhostTransactionFinalized::class);
    }

    public function test_transaction_postback_in_progress_does_not_finalize(): void
    {
        Event::fake();

        $txId = 'dcb9f104-42d4-48c7-9b0f-46b1129d862c';
        Transaction::factory()->create(['id' => $txId, 'status' => TransactionStatus::WaitingForSigner->value]);

        $status = TransactionStatus::InProgress->value;
        $checksum = sha1($txId.'||'.$status.'|'.config('signhost.webhook.secret'));

        $payload = [
            'Id' => $txId,
            'Status' => $status,
            'Checksum' => $checksum,
            'Signers' => [],
        ];

        $response = $this->withHeaders([
            'Authorization' => 'test-token',
        ])->postJson(route('laravel-signhost.postback.transaction'), $payload);

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        Event::assertNotDispatched(SignhostTransactionFinalized::class);
    }

    public function test_idproof_postback_persists_files_links_and_receivers(): void
    {
        Event::fake();

        $status = TransactionStatus::Signed->value;
        $txId = 'dcb9f104-42d4-48c7-9b0f-46b1129d862c';
        $checksum = sha1($txId.'||'.$status.'|'.config('signhost.webhook.secret'));

        $payload = [
            'Id' => $txId,
            'Files' => [
                'Document A' => [
                    'Links' => [
                        ['Rel' => 'file', 'Type' => 'application/pdf', 'Link' => 'https://example.com/file-a.pdf'],
                        ['Rel' => 'receipt', 'Type' => 'application/pdf', 'Link' => 'https://example.com/receipt-a.pdf'],
                    ],
                ],
            ],
            'Language' => 'en-US',
            'Seal' => false,
            'Signers' => [],
            'Receivers' => [
                [
                    'Name' => 'Jane Roe',
                    'Email' => 'jane@example.com',
                    'Language' => 'en-US',
                    'Message' => 'Hello',
                    'Reference' => 'ref-1',
                    'Context' => ['foo' => 'bar'],
                    'Id' => 'rcv-1',
                    'CreatedDateTime' => '2025-10-14T10:00:00+00:00',
                    'ModifiedDateTime' => '2025-10-14T11:00:00+00:00',
                ],
            ],
            'Reference' => 'idp-123',
            'PostbackUrl' => 'https://example.com/webhook/idproof',
            'SignRequestMode' => SignRequestMode::AtOnce->value,
            'DaysToExpire' => 30,
            'SendEmailNotifications' => false,
            'Status' => $status,
            'CancelationReason' => null,
            'Context' => ['findings' => ['authenticated' => true, 'probability' => 87]],
            'CreatedDateTime' => '2025-10-14T12:00:00+00:00',
            'ModifiedDateTime' => '2025-10-14T12:10:00+00:00',
            'CanceledDateTime' => null,
            'Checksum' => $checksum,
        ];

        $response = $this->withHeaders([
            'Authorization' => 'test-token',
        ])->postJson(route('laravel-signhost.postback.idproof'), $payload);

        $response->assertStatus(200)
            ->assertJsonPath('success', true);

        // Transaction persisted with context-derived flags
        $this->assertDatabaseHas('sh_transactions', [
            'id' => $txId,
            'status' => $status,
            'type' => 'id_proof',
            'authenticated' => true,
            'probability' => 87,
        ]);

        // File entries and links persisted (display_name is encrypted in DB, so assert via model)
        $file = File::query()->where('transaction_id', $txId)->first();
        $this->assertNotNull($file);
        $this->assertSame('Document A', $file->display_name);

        $this->assertDatabaseHas('sh_transaction_file_links', [
            'rel' => 'file',
            'type' => 'application/pdf',
            'link' => 'https://example.com/file-a.pdf',
        ]);
        $this->assertDatabaseHas('sh_transaction_file_links', [
            'rel' => 'receipt',
            'type' => 'application/pdf',
            'link' => 'https://example.com/receipt-a.pdf',
        ]);

        // Receivers persisted
        $this->assertDatabaseHas('sh_transaction_receivers', [
            'id' => 'rcv-1',
            'transaction_id' => $txId,
            'email' => 'jane@example.com',
            'language' => 'en-US',
        ]);

        Event::assertDispatched(SignhostIdProofReceived::class);
    }
}
