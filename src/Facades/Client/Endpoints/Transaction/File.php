<?php

namespace Noardcode\LaravelSignhost\Facades\Client\Endpoints\Transaction;

use Exception;
use finfo;
use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Noardcode\LaravelSignhost\Facades\SignhostClient;
use Noardcode\LaravelSignhost\Facades\SignhostService;
use Noardcode\LaravelSignhost\Models\File as FileModel;
use Noardcode\LaravelSignhost\Models\Transaction as TransactionModel;

/**
 * Endpoint Transaction File
 *
 * Handles file operations for a Signhost transaction: posting metadata,
 * uploading PDF content, and downloading signed documents.
 *
 * @endpoint TransactionFile
 */
class File
{
    public function __construct(protected SignhostClient $client) {}

    /**
     * @param  TransactionModel  $transaction
     * @param  FileModel  $file
     * @return bool
     *
     * @throws Exception
     */
    public function create(TransactionModel $transaction, FileModel $file): bool
    {
        if (! empty($file->meta_data)) {
            $this->postMetaData($transaction, $file);
        }

        $response = $this->update($transaction, $file);

        if (! $response->successful()) {
            throw new Exception('Error occurred while uploading document: '.$response->body());
        }

        return $file->update(['content_exported' => true]);
    }

    /**
     * @param  TransactionModel  $transaction
     * @param  FileModel  $file
     * @return Response
     *
     * @throws Exception
     */
    public function update(TransactionModel $transaction, FileModel $file): Response
    {
        $disk = app(SignhostService::class)->getDisk();
        $content = Storage::disk($disk)->get($file?->original_file_path);

        if ((new finfo(FILEINFO_MIME_TYPE))->buffer($content) !== 'application/pdf') {
            throw new Exception('File is not of type application/pdf');
        }

        $uri = '/transaction/'.$transaction->id.'/file/'.$file?->id;
        $digest = base64_encode(hash('sha256', $content, true));

        return Http::withHeaders($this->client->getHeaders([
            'Content-Type' => 'application/pdf',
            'Digest' => "SHA-256=$digest",
        ]))->withBody($content, 'application/pdf')
            ->put($this->client->getApiUrl($uri));
    }

    /**
     * @param  string  $transactionId
     * @param  string  $fileId
     * @return Response|PromiseInterface
     *
     * @throws Exception
     */
    public function get(string $transactionId, string $fileId): Response|PromiseInterface
    {
        return Http::withHeaders($this->client->getHeaders([
            'Accept' => 'application/pdf',
        ]))->get($this->client->getApiUrl('/transaction/'.$transactionId.'/file/'.$fileId));
    }

    /**
     * @param  TransactionModel  $transaction
     * @param  FileModel  $file
     * @return void
     *
     * @throws ConnectionException
     * @throws Exception
     */
    private function postMetaData(TransactionModel $transaction, FileModel $file): void
    {
        $uri = '/transaction/'.$transaction->id.'/file/'.$file?->id;

        $request = [
            'DisplayOrder' => $file->meta_data->getDisplayOrder(),
            'DisplayName' => $file->meta_data->getDisplayName(),
            'SetParaph' => $file->meta_data->getSetParaph(),
        ];

        if (! is_null($file->meta_data->getSigners())) {
            $request['Signers'] = $file->meta_data->getSigners()->toArray();
        }

        if (! is_null($file->meta_data->getFormsets())) {
            $request['FormSets'] = $file->meta_data->getFormsets()->toArray();
        }

        $response = Http::withHeaders($this->client->getHeaders([
            'Content-Type' => 'application/json',
        ]))->put($this->client->getApiUrl($uri), $request);

        if (! $response->successful()) {
            throw new Exception('An exception occurred while sending FileMetaData: '.$response->body());
        }

        $file->update(['meta_data_exported' => true]);
    }
}
