<?php

namespace Noardcode\LaravelSignhost\Repositories;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Storage;
use Noardcode\LaravelSignhost\Exceptions\SignhostException;
use Noardcode\LaravelSignhost\Facades\SignhostService;
use Noardcode\LaravelSignhost\Models\File as FileModel;
use Noardcode\LaravelSignhost\Models\Transaction;
use Noardcode\LaravelSignhost\ValueObjects\Transactions\FileUpload as FileValueObject;

/**
 * Class Files
 */
class Files
{
    public function __construct() {}

    /**
     * Ensure the original file exists on the configured disk.
     *
     * @throws SignhostException
     */
    public function ensureExists(FileModel $file): void
    {
        $disk = app(SignhostService::class)->getDisk();
        if (Storage::disk($disk)->exists($file->original_file_path)) {
            return;
        }
        throw new SignhostException('Original File does not exist on disk "'.$disk.'"');
    }

    /**
     * Store the ID proof document to the configured disk and return its path.
     *
     * @throws SignhostException
     */
    public function storeIdProofDocument(Transaction $transaction, Response $response, string $fileId): string
    {
        $path = 'transactions/'.$transaction->id.'/idproof/'.md5($fileId).'.pdf';
        $this->storeToDisk($response, $path);

        if (! $path) {
            throw new SignhostException('Original file not stored on disk');
        }

        $transaction->files()->create([
            'id' => $fileId,
            'transaction_id' => $transaction->id,
            'display_name' => md5($fileId),
            'original_file_path' => $path,
        ]);

        return $path;
    }

    /**
     * Store the original uploaded file to the configured disk and return its path.
     *
     * @throws FileNotFoundException
     * @throws SignhostException
     */
    public function storeOriginal(Transaction $transaction, FileValueObject $file): string
    {
        $disk = app(SignhostService::class)->getDisk();
        $path = $file->getFile()->store('transactions/'.$transaction->id.'/original', ['disk' => $disk]);

        if (! $path) {
            throw new SignhostException('Original file not stored on disk');
        }

        return $path;
    }

    /**
     * Persist a signed file PDF to disk and return the relative path.
     */
    public function storeSignedFile(Transaction $transaction, Response $response, FileModel $file): string
    {
        $hashSource = ($transaction->transaction_uuid ?? (string) $transaction->id).($file?->id ?? '');
        $path = 'transactions/'.$transaction->id.'/signed/'.md5($hashSource).'.pdf';

        $this->storeToDisk($response, $path);

        return $path;
    }

    /**
     * Persist the receipt PDF to disk and return the relative path.
     *
     * @throws SignhostException
     */
    public function storeReceiptFile(Transaction $transaction, Response $response): string
    {
        $hashSource = $transaction->transaction_uuid ?? (string) $transaction->id;
        $receiptPath = 'transactions/'.$transaction->id.'/receipt/'.md5($hashSource).'.pdf';

        Storage::disk(app(SignhostService::class)->getDisk())->put($receiptPath, $response->body());

        return $receiptPath;
    }

    /**
     * Stream helper for workbench/dev tools.
     */
    public function streamFile(Transaction $transaction, string $path): array
    {
        $file = Storage::disk(app(SignhostService::class)->getDisk())->path($path);

        if (! file_exists($file)) {
            abort('404');
        }

        return [$file, $this->getFileStreamHeaders($transaction)];
    }

    private function getFileStreamHeaders(Transaction $transaction): array
    {
        return [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$transaction->id.'"',
        ];
    }

    private function storeToDisk(Response $response, string $path): void
    {
        Storage::disk(app(SignhostService::class)->getDisk())->put($path, $response->body());
    }
}
