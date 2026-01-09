<?php

namespace Noardcode\LaravelSignhost\Tests\Unit\Facades;

use Noardcode\LaravelSignhost\Exceptions\SignhostException;
use Noardcode\LaravelSignhost\Facades\Services\IdProof;
use Noardcode\LaravelSignhost\Facades\Services\Signing;
use Noardcode\LaravelSignhost\Facades\SignhostService;
use Noardcode\LaravelSignhost\Tests\TestCase;

class SignhostServiceTest extends TestCase
{
    private SignhostService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new SignhostService;
    }

    public function test_service_can_retrieve_signing_service(): void
    {
        $this->assertInstanceOf(Signing::class, $this->service->signing());
    }

    public function test_service_can_retrieve_id_proof_service(): void
    {
        $this->assertInstanceOf(IdProof::class, $this->service->idProof());
    }

    public function test_service_can_retrieve_disk(): void
    {
        $this->assertEquals('signhost', $this->service->getDisk());
    }

    public function test_service_throws_error_on_missing_disk(): void
    {
        // Unset both defaults so getDisk() fails validation
        config()->set('filesystems.default');
        config()->set('signhost.disk');

        $this->expectException(SignhostException::class);
        $this->service->getDisk();
    }
}
