<?php

namespace Noardcode\LaravelSignhost\Facades\Client\Endpoints\Transaction;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Noardcode\LaravelSignhost\Facades\SignhostClient;

/**
 * Endpoint Transaction Receipt
 *
 * Retrieves the receipt PDF for a given Signhost transaction.
 *
 * @endpoint TransactionReceipt
 */
class Receipt
{
    public function __construct(protected SignhostClient $client) {}

    /**
     * @param  int|string  $transactionId
     * @return Response|PromiseInterface
     *
     * @throws ConnectionException
     */
    public function get(int|string $transactionId): Response|PromiseInterface
    {
        return Http::withHeaders($this->client->getHeaders())
            ->get($this->client->getApiUrl('/file/receipt/'.$transactionId));
    }
}
