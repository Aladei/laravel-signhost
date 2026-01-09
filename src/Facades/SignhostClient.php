<?php

namespace Noardcode\LaravelSignhost\Facades;

use Exception;
use Illuminate\Support\Testing\Fakes\Fake;
use Noardcode\LaravelSignhost\Facades\Client\Endpoints\Transaction;
use Noardcode\LaravelSignhost\Facades\Client\SignhostClientFaker;

/**
 * Client SignhostClient
 *
 * Lightweight HTTP client wrapper around the Signhost API used by this package.
 * Exposes endpoint groups (e.g., Transaction) and centralizes common headers
 * and URL construction. Provides a fake() helper via the SignhostClientFaker trait
 * for testing.
 *
 * @client SignhostClient
 */
class SignhostClient implements Fake
{
    use SignhostClientFaker;

    /**
     * @var Transaction|null
     */
    public ?Transaction $transaction = null;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        if (config('signhost.simulation.enabled')) {
            self::fake();
        }
        $this->transaction = new Transaction($this);
    }

    /**
     * @param  string|null  $uri
     * @return string
     */
    public function getApiUrl(?string $uri = null): string
    {
        return rtrim(config('signhost.api.url'), '/').'/'.ltrim($uri ?? '', '/');
    }

    /**
     * @return string
     */
    public function getWebhookSecret(): string
    {
        return config('signhost.webhook.secret');
    }

    /**
     * @param  string  $transactionId
     * @param  string  $status
     * @return string
     */
    public function createWebhookChecksum(string $transactionId, string $status): string
    {
        return sha1("{$transactionId}||{$status}|{$this->getWebhookSecret()}");
    }

    /**
     * @return string
     */
    public function getWebhookToken(): string
    {
        return config('signhost.webhook.token');
    }

    /**
     * @return string
     */
    protected function getAppKey(): string
    {
        return config('signhost.api.app_key');
    }

    /**
     * @return string
     */
    protected function getApiKey(): string
    {
        return config('signhost.api.user_token');
    }

    /**
     * Build default HTTP headers for Signhost requests.
     *
     * Note: Endpoints can override Accept for PDFs, otherwise defaults to
     * Accept: application/vnd.signhost.v1+json.
     *
     * @param  array  $additionHeaders
     * @return array
     */
    public function getHeaders(array $additionHeaders = []): array
    {
        return array_merge([
            'Accept' => 'application/vnd.signhost.v1+json',
            'Authorization' => 'APIKey '.$this->getApiKey(),
            'Application' => 'APPKey '.$this->getAppKey(),
        ], $additionHeaders);
    }

    /**
     * @return self
     */
    public static function getClient(): self
    {
        return new self;
    }
}
