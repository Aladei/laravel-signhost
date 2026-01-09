<?php

namespace Noardcode\LaravelSignhost\Tests\Feature\Facades;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Noardcode\LaravelSignhost\Facades\SignhostClient;
use Noardcode\LaravelSignhost\Tests\TestCase;

class FileAndReceiptEndpointsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('signhost.api.user_token', 'test-user-token');
        config()->set('signhost.api.app_key', 'test-app-key');
    }

    public function test_file_get_endpoint_uses_faker_and_returns_response(): void
    {
        SignhostClient::fake();

        $client = SignhostClient::getClient();
        $response = $client->transaction->file->get('tx-123', 'file-456');

        $this->assertSame(201, $response->status());
    }

    public function test_receipt_get_endpoint_uses_faker_and_returns_pdf(): void
    {
        SignhostClient::fake();

        $client = SignhostClient::getClient();
        $response = $client->transaction->receipt->get('tx-123');

        $this->assertTrue($response->successful());
        $this->assertSame('application/pdf', $response->header('Content-Type'));
    }
}
