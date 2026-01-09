<?php

namespace Noardcode\LaravelSignhost\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Noardcode\LaravelSignhost\Enums\TransactionType;
use Noardcode\LaravelSignhost\Models\Transaction;
use Noardcode\LaravelSignhost\ValueObjects\IdProof;
use Throwable;

/**
 * Class IdProofs
 */
class IdProofs
{
    /**
     * @param  IdProof  $vo
     * @return Transaction|null
     *
     * @throws Throwable
     */
    public function storeIdProof(IdProof $vo): ?Transaction
    {
        return DB::transaction(function () use ($vo) {
            $transaction = Transaction::query()->updateOrCreate([
                'id' => $vo->getId(),
            ], [
                'type' => TransactionType::IdProof,
                'status' => $vo->getStatus(),
                'seal' => $vo->getSeal(),
                'reference' => $vo->getReference(),
                'postback_url' => $vo->getPostbackUrl(),
                'sign_request_mode' => $vo->getSignRequestMode(),
                'days_to_expire' => $vo->getDaysToExpire(),
                'send_email_notifications' => $vo->getSendEmailNotifications(),
                'created_date_time' => $vo->getCreatedDateTime(),
                'modified_date_time' => $vo->getModifiedDateTime(),
                'canceled_date_time' => $vo->getCanceledDateTime(),
                'authenticated' => filter_var(json_decode($vo->getContext())
                    ?->findings?->authenticated ?? false, FILTER_VALIDATE_BOOLEAN),
                'probability' => json_decode($vo->getContext())?->findings?->probability ?? 0,
                'context' => is_string($vo->getContext()) ? $vo->getContext() : json_encode($vo->getContext()),
                'receipt' => null,
                'object' => null,
            ]);

            // Store receivers.
            foreach ($vo->getReceivers() as $receiver) {
                $transaction->receivers()->updateOrCreate([
                    'id' => $receiver->getId(),
                ], [
                    'name' => $receiver->getName(),
                    'email' => $receiver->getEmail(),
                    'language' => $receiver->getLanguage()->value,
                    'message' => $receiver->getMessage(),
                    'reference' => $receiver->getReference(),
                    'created_date_time' => $receiver->getCreatedDateTime(),
                    'modified_date_time' => $receiver->getModifiedDateTime(),
                    'context' => $receiver->getContext(),
                ]);
            }

            // Delete previous files.
            foreach ($transaction->files as $file) {
                $file->links()->delete();
                $file->delete();
            }

            // Store files.
            foreach ($vo->getFileEntries() as $file) {
                $fileModel = $transaction->files()->create([
                    'id' => Str::uuid(),
                    'display_name' => $file->getDisplayName(),
                ]);

                // Store links.
                foreach ($file->getLinks() as $link) {
                    $fileModel->links()->create([
                        'id' => Str::uuid(),
                        'rel' => $link->getRel()->value,
                        'type' => $link->getType(),
                        'link' => $link->getLink(),
                    ]);
                }
            }

            return $transaction;
        });
    }
}
