<?php

namespace Noardcode\LaravelSignhost\Tests\Unit\Services;

use Noardcode\LaravelSignhost\Exceptions\SignhostException;
use Noardcode\LaravelSignhost\Facades\Services\IdProof;
use Noardcode\LaravelSignhost\Tests\TestCase;

class IdProofServiceTest extends TestCase
{
    public function test_redirect_builds_url_and_encodes_identifier(): void
    {
        config()->set('signhost.id_proof.form_url', 'https://example.com/form');

        $service = new IdProof;
        $response = $service->redirectToSignhost('id with spaces & symbols');

        $this->assertTrue($response->isRedirect());
        $this->assertSame(
            'https://example.com/form?q='.urlencode('id with spaces & symbols'),
            $response->getTargetUrl()
        );
    }

    public function test_invalid_identifier_with_disallowed_chars_throws(): void
    {
        config()->set('signhost.id_proof.form_url', 'https://example.com/form');

        $service = new IdProof;

        $this->expectException(SignhostException::class);
        $service->redirectToSignhost('bad:identifier');
    }

    public function test_invalid_identifier_with_control_chars_throws(): void
    {
        config()->set('signhost.id_proof.form_url', 'https://example.com/form');

        $service = new IdProof;

        $this->expectException(SignhostException::class);
        $this->expectExceptionMessage('Identifier contains control characters which are not allowed.');
        $service->redirectToSignhost("line\nbreak");
    }

    public function test_empty_identifier_throws(): void
    {
        config()->set('signhost.id_proof.form_url', 'https://example.com/form');

        $service = new IdProof;

        $this->expectException(SignhostException::class);
        $this->expectExceptionMessage('Identifier must not be empty.');
        $service->redirectToSignhost('');
    }

    public function test_identifier_exceeds_max_length_throws(): void
    {
        config()->set('signhost.id_proof.form_url', 'https://example.com/form');

        $service = new IdProof;
        $tooLong = str_repeat('a', 256);

        $this->expectException(SignhostException::class);
        $this->expectExceptionMessage('Identifier must not exceed 255 characters.');
        $service->redirectToSignhost($tooLong);
    }
}
