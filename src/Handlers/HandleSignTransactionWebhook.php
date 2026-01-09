<?php

namespace Noardcode\LaravelSignhost\Handlers;

use Exception;
use Illuminate\Support\Facades\Log;
use JsonException;
use Noardcode\LaravelSignhost\Enums\TransactionStatus;
use Noardcode\LaravelSignhost\Events\SignhostTransactionFinalized;
use Noardcode\LaravelSignhost\Mappers\TransactionWebhookMapper;
use Noardcode\LaravelSignhost\Models\Activity;
use Noardcode\LaravelSignhost\Models\Transaction;
use Throwable;

/**
 * Handler HandleSignTransactionWebhook
 *
 * Processes incoming Signhost Transaction webhook payloads. The raw JSON body
 * is decoded and normalized via TransactionWebhookMapper. The handler persists
 * signer activities, updates the transaction status, and dispatches a
 * SignhostTransactionFinalized event when the transaction reaches the Signed
 * state.
 *
 * @handler HandleSignTransactionWebhook
 */
class HandleSignTransactionWebhook
{
    /**
     * Create the handler with its required dependencies.
     *
     * @param  TransactionWebhookMapper  $mapper  Mapper to transform the webhook payload
     */
    public function __construct(
        private readonly TransactionWebhookMapper $mapper,
    ) {}

    /**
     * @param  string  $json
     * @return void
     *
     * @throws JsonException
     * @throws Throwable
     */
    public function __invoke(string $json): void
    {
        $collection = collect(json_decode($json, true, 20, JSON_THROW_ON_ERROR));

        try {
            $transactionVo = $this->mapper->fromCollection($collection);

            $transaction = $this->getTransaction($transactionVo->getId());

            foreach ($transactionVo->getSigners() ?? [] as $signer) {
                $signerId = $this->getTransactionReceiver($transaction, $signer->getId());

                if (empty($signer->getActivities())) {
                    continue;
                }

                foreach ($signer->getActivities() as $activity) {
                    Activity::query()->firstOrCreate([
                        'id' => $activity->getId(),
                        'transaction_id' => $transaction->id,
                    ], [
                        'transaction_signer_id' => $signerId ?? $signer->getId(),
                        'state' => $activity->getCode(),
                        'state_code' => $activity->getCode()->value,
                        'created_at' => $activity->getCreatedDateTime(),
                    ]);
                }
            }

            $transaction->update([
                'status' => $transactionVo->getStatus()->value,
                'webhook_response' => $json,
            ]);

            if ($transactionVo->getStatus() === TransactionStatus::Signed) {
                SignhostTransactionFinalized::dispatch($transaction);
            }

        } catch (Exception $e) {
            if (app()->runningUnitTests()) {
                throw $e;
            }
            Log::debug($e->getMessage());
            if (isset($transactionVo)) {
                Log::debug(json_encode($transactionVo->toArray()));
            }
        }
    }

    /**
     * @param  string|int  $transactionId
     * @return Transaction
     */
    private function getTransaction(string|int $transactionId): object
    {
        return Transaction::query()
            ->where('id', $transactionId)
            ->firstOrFail();
    }

    /**
     * @param  Transaction  $transaction
     * @param  string  $uuid
     * @return string|null
     */
    private function getTransactionReceiver(Transaction $transaction, string $uuid): ?string
    {
        return Transaction\Receiver::query()
            ->where('transaction_id', $transaction->id)
            ->where('id', $uuid)->first()?->id;
    }
}
