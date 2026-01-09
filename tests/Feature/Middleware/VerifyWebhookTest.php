<?php

namespace Noardcode\LaravelSignhost\Tests\Feature\Middleware;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Noardcode\LaravelSignhost\Enums\TransactionStatus;
use Noardcode\LaravelSignhost\Models\Transaction;
use Noardcode\LaravelSignhost\Tests\TestCase;

class VerifyWebhookTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Ensure webhook secrets exist during tests.
        config()->set('signhost.webhook.token', 'test-token');
        config()->set('signhost.webhook.secret', 'test-secret');
    }

    public function test_missing_authorization_header_returns_message(): void
    {
        $response = $this->postJson(route('laravel-signhost.postback.transaction'), []);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Authorization header is missing.');
    }

    public function test_wrong_authorization_header_returns_message(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'wrong-token',
        ])->postJson(route('laravel-signhost.postback.transaction'), []);

        $response->assertStatus(200)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Authorization header is incorrect.');
    }

    public function test_missing_required_data_returns_message(): void
    {
        $response = $this->withHeaders([
            'Authorization' => 'test-token',
        ])->postJson(route('laravel-signhost.postback.transaction'), [
            // Intentionally missing Id/Checksum/Status
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Missing required data.');
    }

    public function test_wrong_checksum_returns_message(): void
    {
        $txId = 'dcb9f104-42d4-48c7-9b0f-46b1129d862c';
        $payload = [
            'Id' => $txId,
            'Status' => TransactionStatus::InProgress->value,
            'Checksum' => 'invalid-checksum',
        ];

        $response = $this->withHeaders([
            'Authorization' => 'test-token',
        ])->postJson(route('laravel-signhost.postback.transaction'), $payload);

        $response->assertStatus(200)
            ->assertJsonPath('success', false)
            ->assertJsonPath('message', 'Checksums are not equal.');
    }

    public function test_valid_payload_passes_through_middleware(): void
    {
        // Create the transaction so the downstream handler won't fail resolving it.
        $txId = 'dcb9f104-42d4-48c7-9b0f-46b1129d862c';
        Transaction::factory()->create(['id' => $txId]);

        $status = TransactionStatus::InProgress->value;
        $checksum = sha1($txId.'||'.$status.'|'.config('signhost.webhook.secret'));

        $payload = [
            'Id' => $txId,
            'Status' => $status,
            'Checksum' => $checksum,
            // Minimal body acceptable by handler (no Signers)
        ];

        $response = $this->withHeaders([
            'Authorization' => 'test-token',
        ])->postJson(route('laravel-signhost.postback.transaction'), $payload);

        $response->assertStatus(200)
            ->assertJsonPath('success', true)
            ->assertJsonPath('message', 'Sign Transaction webhook received.');
    }
}
