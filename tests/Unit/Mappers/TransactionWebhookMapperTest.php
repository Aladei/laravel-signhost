<?php

namespace Noardcode\LaravelSignhost\Tests\Unit\Mappers;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Noardcode\LaravelSignhost\Mappers\TransactionWebhookMapper;
use Noardcode\LaravelSignhost\Tests\TestCase;

class TransactionWebhookMapperTest extends TestCase
{
    public function test_mapper_includes_additional_fields_from_example_payload(): void
    {
        $payload = [
            'Id' => 'b10ae331-af78-4e79-a39e-5b64693b6b68',
            'Status' => 20,
            'Seal' => true,
            'Language' => 'en-US',
            'Signers' => [
                [
                    'Id' => 'fa95495d-6c59-48e0-962a-a4552f8d6b85',
                    'Email' => 'user@example.com',
                    'Mobile' => '+31612345678',
                    'RequireScribbleName' => false,
                    'RequireScribble' => true,
                    'RequireEmailVerification' => true,
                    'RequireSmsVerification' => true,
                    'RequireIdealVerification' => false,
                    'RequireDigidVerification' => false,
                    'RequireSurfnetVerification' => false,
                    'SendSignRequest' => true,
                    'SendSignConfirmation' => true,
                    'SignRequestMessage' => 'Hello, could you please sign this document? Best regards, John Doe',
                    'DaysToRemind' => 15,
                    'Language' => 'en-US',
                    'ScribbleName' => 'John Doe',
                    'ScribbleNameFixed' => false,
                    'Reference' => 'Client #123',
                    'ReturnUrl' => 'https://signhost.com',
                    'Activities' => [
                        [
                            'Id' => 'bcba44a9-c201-4494-9920-2c1f7baebcf0',
                            'Code' => 103,
                            'Activity' => 'Opened',
                            'CreatedDateTime' => '2016-06-15T23:33:04.1965465+02:00',
                        ],
                        [
                            'Id' => 'de94cf6e-e1a3-4c33-93bf-2013b036daaf',
                            'Code' => 203,
                            'Activity' => 'Signed',
                            'CreatedDateTime' => '2016-06-15T23:38:04.1965465+02:00',
                        ],
                    ],
                    'SignUrl' => 'https://view.signhost.com/sign/d3c93bd6-f1ce-48e7-8c9c-c2babfdd4034',
                    'CreatedDateTime' => '2016-06-15T23:33:04.1965465+02:00',
                    'ModifiedDateTime' => '2016-06-15T23:33:04.1965465+02:00',
                ],
            ],
            'Receivers' => [
                [
                    'Id' => '97ed6b54-b6d1-46ed-88c1-79779c3b47b1',
                    'Name' => 'John Doe',
                    'Email' => 'user@example.com',
                    'Language' => 'en-US',
                    'Message' => 'Hello, please find enclosed the digital signed document. Best regards, John Doe',
                    'CreatedDateTime' => '2016-06-15T23:33:04.1965465+02:00',
                    'ModifiedDateTime' => '2016-06-15T23:33:04.1965465+02:00',
                    'Activities' => [],
                ],
            ],
            'Reference' => 'Contract #123',
            'PostbackUrl' => 'https://example.com/postback.php',
            'SignRequestMode' => 2,
            'DaysToExpire' => 30,
            'SendEmailNotifications' => true,
            'CreatedDateTime' => '2016-08-31T21:22:56.2467731+02:00',
            'ModifiedDateTime' => '2016-08-31T21:22:56.2467731+02:00',
            'Checksum' => 'b5a99e1de5b9e0e9915df09d3b819be188dae900',
        ];

        $mapper = new TransactionWebhookMapper;
        $result = $mapper->fromCollection(new Collection($payload));

        // Top-level checks
        $this->assertSame($payload['Id'], $result->getId());
        $this->assertSame($payload['Status'], $result->getStatus()->value);
        $this->assertTrue($result->getSeal());
        $this->assertSame('en-US', $result->getLanguage()->value);
        $this->assertSame($payload['Reference'], $result->getReference());
        $this->assertSame($payload['PostbackUrl'], $result->getPostbackUrl());
        $this->assertSame($payload['SignRequestMode'], $result->getSignRequestMode()->value);
        $this->assertSame($payload['DaysToExpire'], $result->getDaysToExpire());
        $this->assertSame($payload['SendEmailNotifications'], $result->getSendEmailNotifications());

        // Receivers
        $this->assertNotEmpty($result->getReceivers());
        $this->assertSame('97ed6b54-b6d1-46ed-88c1-79779c3b47b1', $result->getReceivers()[0]->getId());
        $this->assertSame('John Doe', $result->getReceivers()[0]->getName());
        $this->assertSame('user@example.com', $result->getReceivers()[0]->getEmail());
        $this->assertSame('en-US', $result->getReceivers()[0]->getLanguage()->value);
        $this->assertEquals(Carbon::parse('2016-06-15T23:33:04.1965465+02:00'), $result->getReceivers()[0]->getCreatedDateTime());

        // Signers
        $this->assertNotEmpty($result->getSigners());
        $signer = $result->getSigners()[0];
        $this->assertSame('fa95495d-6c59-48e0-962a-a4552f8d6b85', $signer->getId());
        $this->assertTrue($signer->getRequireScribble());
        $this->assertTrue($signer->getRequireEmailVerification());
        $this->assertTrue($signer->getRequireSmsVerification());
        $this->assertTrue($signer->getSendSignRequest());
        $this->assertTrue($signer->getSendSignConfirmation());
        $this->assertSame('en-US', $signer->getLanguage()->value);
        $this->assertSame('John Doe', $signer->getScribbleName());
        $this->assertSame('Client #123', $signer->getReference());
        $this->assertSame('https://signhost.com', $signer->getReturnUrl());
        $this->assertCount(2, $signer->getActivities());
        $this->assertSame(203, $signer->getActivities()[1]->getCode()->value);
    }
}
