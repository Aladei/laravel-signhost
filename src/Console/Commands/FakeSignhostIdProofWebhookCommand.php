<?php

namespace Noardcode\LaravelSignhost\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Noardcode\LaravelSignhost\Facades\SignhostClient;
use Random\RandomException;

class FakeSignhostIdProofWebhookCommand extends Command
{
    protected $signature = 'signhost:fake-id-proof-webhook 
                            {identifier : The transaction identifier to use in the postback url}
                            {--t|--transaction-id= : Optional transaction id (UUID). If omitted, a UUID will be generated}
                            {--u|--postback-url= : Override the webhook postback url}';

    protected $description = 'Fake a Signhost ID Proof webhook call for a transaction, using database data and filling missing fields with fake data.';

    /**
     * @throws RandomException
     */
    public function handle(): int
    {
        $identifier = $this->argument('identifier');

        $transactionId = $this->option('transaction-id') ?: (string) Str::uuid();
        $now = Carbon::now();

        $payload = $this->buildPayload($identifier, $transactionId, $now);

        if (config('signhost.simulation.webhooks.id_proof')) {
            $webhookUrl = config('signhost.simulation.webhooks.id_proof');
        } else {
            $webhookUrl = $this->option('postback-url') ?: route('laravel-signhost.postback.idproof', [], false);
            if (! str_starts_with($webhookUrl, 'http')) {
                $webhookUrl = rtrim(config('app.url', ''), '/').'/'.ltrim($webhookUrl, '/');
            }
        }

        $authorization = config('signhost.webhook.token');
        $headers = [];
        if ($authorization) {
            $headers['Authorization'] = $authorization;
        }

        try {
            $response = $this->sendWebhook($webhookUrl, $payload, $headers);
        } catch (\Throwable $e) {
            $this->error('Failed to send webhook: '.$e->getMessage());

            return 2;
        }

        $this->info('Fake Signhost IdProof webhook sent!');
        $this->table(['Field', 'Value'], [
            ['Transaction ID', $transactionId],
            ['Identifier', $identifier],
            ['Validation', 'Success'],
            ['Endpoint', $webhookUrl],
            ['Json', json_encode($response->json())],
            ['Status', $response->status()],
        ]);

        if (! $response->successful()) {
            $this->warn('Response body:');
            $this->line($response->body());

            return 3;
        }

        return 0;
    }

    /**
     * @throws RandomException
     */
    private function buildPayload(string $identifier, string $transactionId, Carbon $now): array
    {
        $created = $now->copy()->toIso8601String();
        $modified = $now->copy()->toIso8601String();
        $dossierCreated = $now->copy()->subHours(2)->toIso8601String();

        $probabilityMin = random_int(300, 700);
        $probabilityMax = random_int($probabilityMin, 1000);

        $verifications = [
            ['name' => 'Document Data Comparison', 'verificationType' => '709', 'version' => '4.5.0.3853'],
            ['name' => 'Document Ensemble Authenticator', 'verificationType' => '202', 'version' => '4.5.0.3853'],
            ['name' => 'Black And White Copy', 'verificationType' => '102', 'version' => '4.5.0.3853'],
            ['name' => 'Data Comparison', 'verificationType' => '700', 'version' => '4.5.0.3853'],
            ['name' => 'Ensemble Authenticator', 'verificationType' => '201', 'version' => '4.5.0.3853'],
            ['name' => 'Human Face Presence', 'verificationType' => '300', 'version' => '4.5.0.3853'],
            ['name' => 'ID Document Blacklist', 'verificationType' => '101', 'version' => '4.5.0.3853'],
            ['name' => 'MRZ Check Digit', 'verificationType' => '601', 'version' => '4.5.0.3853'],
            ['name' => 'Face Comparison', 'verificationType' => '301', 'version' => '1.0'],
            ['name' => 'Face Liveness', 'verificationType' => '302', 'version' => '1.0'],
            ['name' => 'Document Liveness', 'verificationType' => '108', 'version' => '2.2'],
            ['name' => 'Portrait Substitution', 'verificationType' => '305', 'version' => '2.2'],
        ];

        $mapped = array_map(function ($v) use ($probabilityMin, $probabilityMax) {
            return array_merge($v, [
                'probability' => (string) random_int($probabilityMin, $probabilityMax),
                'judgement' => 'Authentic',
                'documentId' => (string) Str::uuid(),
                'notifications' => [],
            ]);
        }, $verifications);

        $probabilities = array_map(fn ($v) => (int) $v['probability'], $mapped);
        $avgProbability = count($probabilities) ? (string) (int) (array_sum($probabilities) / count($probabilities)) : '0';

        return [
            'Id' => $transactionId,
            'Status' => 30,
            'Files' => [
                'user-Identitydossier-OCR.pdf' => [
                    'Links' => [
                        [
                            'Rel' => 'file',
                            'Type' => 'application/pdf',
                            'Link' => "https://api.signhost.com/api/transaction/{$transactionId}/file/user-Identitydossier-OCR.pdf",
                        ],
                    ],
                    'DisplayName' => 'user-Identitydossier-OCR.pdf',
                ],
            ],
            'Seal' => true,
            'Signers' => [],
            'Receivers' => [],
            'Reference' => 'Contract #385',
            'PostbackUrl' => 'https://example.com/id-proof/?q='.$identifier,
            'SignRequestMode' => 1,
            'DaysToExpire' => 30,
            'SendEmailNotifications' => true,
            'CreatedDateTime' => $created,
            'ModifiedDateTime' => $modified,
            'CanceledDateTime' => null,
            'Context' => [
                'dossierMetadata' => [
                    'processingStatus' => 'Successful',
                    'dossierId' => (string) Str::uuid(),
                    'createdDateTime' => $dossierCreated,
                    'version' => 'v2.1.0',
                ],
                'evidence' => [
                    [
                        'images' => [
                            [
                                'processingStatus' => 'Successful',
                                'processingReasons' => [],
                                'imageId' => (string) Str::uuid(),
                                'extractedData' => [
                                    'dateOfExpiry' => '2030-12-10',
                                    'address' => ['dynamicProperties' => []],
                                    'documentNumber' => 'FAKE123456',
                                    'dynamicProperties' => [
                                        'mrzGivenNames' => 'JOHN DOE',
                                        'sIDE' => '1',
                                        'mrzIssuingCountry' => 'NLD',
                                        'ocrDateOfIssue' => '2020-01-01',
                                        'ocrSurname' => 'Doe',
                                        'ocrPlaceOfBirth' => 'Amsterdam',
                                        'ocrGivenNames' => 'John Doe',
                                        'nationalityCode' => 'NLD',
                                        'personalGovID' => '000000000',
                                        'personalGovID_CHECK' => '0',
                                        'mrzNationalityCode' => 'NLD',
                                        'mrzSIDE' => '1',
                                        'mrzDOCUMENT_IDENTIFIER_ADDITIONAL_CHARACTER' => '<',
                                        'twoLetterSex' => null,
                                        'mrzPersonalGovID' => '000000000',
                                        'lINE_CHECK' => '0',
                                        'tYPE' => 'PASSPORT',
                                        'mrzDOCUMENT_IDENTIFIER_CHARACTER' => 'P',
                                        'mrzDateOfBirth' => '1990-01-01',
                                        'spouseLastName' => null,
                                        'nationality' => 'Nederlandse',
                                        'dOCUMENT_IDENTIFIER_ADDITIONAL_CHARACTER' => '<',
                                        'mrzLine1' => 'MRZLINE1',
                                        'mrzLine2' => 'MRZLINE2',
                                        'mrzTYPE' => 'PASSPORT',
                                        'documentNumber_CHECK' => '0',
                                        'mrzDateOfExpiry' => '2030-12-10',
                                        'ocrDocumentNumber' => 'FAKE123456',
                                        'mrzSex' => 'M',
                                        'ocrDateOfBirth' => '1990-01-01',
                                        'ocrDateOfExpiry' => '2030-12-10',
                                        'ocrSpouseLastName' => null,
                                        'ocrTwoLetterSex' => null,
                                        'countryCode' => 'NLD',
                                        'mrzLINE_CHECK' => '0',
                                        'dOCUMENT_IDENTIFIER_CHARACTER' => 'P',
                                        'ocrNationality' => 'Nederlandse',
                                        'placeOfBirth' => 'Amsterdam',
                                        'sex' => 'M',
                                        'issuingAuthority' => 'Fake Authority',
                                        'mrzSurname' => 'DOE',
                                        'mrzPersonalGovID_CHECK' => '0',
                                        'ocrCountryCode' => 'NLD',
                                        'issuingCountry' => 'NLD',
                                        'mrzDocumentNumber' => 'FAKE123456',
                                        'mrzDocumentNumber_CHECK' => '0',
                                        'ocrSex' => 'M',
                                        'ocrIssuingAuthority' => 'Fake Authority',
                                    ],
                                    'name' => [
                                        'fullName' => 'John Doe',
                                        'surname' => 'Doe',
                                        'dynamicProperties' => [],
                                        'givenNames' => 'John Doe',
                                    ],
                                    'dateOfBirth' => '1990-01-01',
                                    'dateOfIssue' => '2020-01-01',
                                ],
                                'derivedImages' => [],
                                'classification' => [
                                    'mdsid' => 'MDS.2.0.NLD..PP.STD.013014.01',
                                    'imageType' => 'PassportPicturePage',
                                ],
                            ],
                        ],
                        'type' => 'IdDocument',
                        'extractedData' => [
                            'dateOfExpiry' => '2030-12-10',
                            'address' => ['dynamicProperties' => []],
                            'documentNumber' => 'FAKE123456',
                            'dynamicProperties' => [
                                'placeOfBirth' => 'Amsterdam',
                                'sIDE' => '1',
                                'twoLetterSex' => null,
                                'sex' => 'M',
                                'lINE_CHECK' => '0',
                                'documentNumber_CHECK' => '0',
                                'tYPE' => 'PASSPORT',
                                'spouseLastName' => null,
                                'issuingAuthority' => 'Fake Authority',
                                'nationalityCode' => 'NLD',
                                'nationality' => 'Nederlandse',
                                'dOCUMENT_IDENTIFIER_ADDITIONAL_CHARACTER' => '<',
                                'countryCode' => 'NLD',
                                'issuingCountry' => 'NLD',
                                'personalGovID' => '000000000',
                                'dOCUMENT_IDENTIFIER_CHARACTER' => 'P',
                                'personalGovID_CHECK' => '0',
                            ],
                            'name' => [
                                'fullName' => 'JOHN DOE',
                                'surname' => 'DOE',
                                'dynamicProperties' => [],
                                'givenNames' => 'JOHN',
                            ],
                            'dateOfBirth' => '1990-01-01',
                            'dateOfIssue' => '2020-01-01',
                        ],
                        'evidenceId' => (string) Str::uuid(),
                    ],
                    [
                        'type' => 'Biometric',
                        'evidenceId' => (string) Str::uuid(),
                    ],
                ],
                'configuration' => [
                    'verifications' => [],
                    'responseImages' => [],
                ],
                'findings' => [
                    'authenticated' => 'true',
                    'verifications' => $mapped,
                    'probability' => $avgProbability,
                ],
            ],
            'Checksum' => SignhostClient::getClient()->createWebhookChecksum(
                transactionId: $transactionId,
                status: 30
            ),
        ];
    }

    private function sendWebhook(string $url, array $payload, array $headers = []): Response
    {
        return Http::withHeaders(array_merge(['Accept' => 'application/json'], $headers))
            ->timeout(10)
            ->post($url, $payload);
    }
}
