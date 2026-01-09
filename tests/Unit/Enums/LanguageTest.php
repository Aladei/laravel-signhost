<?php

namespace Noardcode\LaravelSignhost\Tests\Unit\Enums;

use Noardcode\LaravelSignhost\Enums\Language;
use Noardcode\LaravelSignhost\Tests\TestCase;

class LanguageTest extends TestCase
{
    public function test_values_and_labels(): void
    {
        $this->assertSame('nl-NL', Language::Dutch->value);
        $this->assertSame('en-US', Language::English->value);
        $this->assertSame('fr-FR', Language::French->value);
        $this->assertSame('de-DE', Language::German->value);
        $this->assertSame('it-IT', Language::Italian->value);
        $this->assertSame('pl-PL', Language::Polish->value);
        $this->assertSame('es-ES', Language::Spanish->value);

        $this->assertSame('Dutch', Language::Dutch->label());
        $this->assertSame('English', Language::English->label());
        $this->assertSame('French', Language::French->label());
        $this->assertSame('German', Language::German->label());
        $this->assertSame('Italian', Language::Italian->label());
        $this->assertSame('Polish', Language::Polish->label());
        $this->assertSame('Spanish', Language::Spanish->label());
    }

    public function test_from_returns_expected_case(): void
    {
        $this->assertSame(Language::Dutch, Language::from('nl-NL'));
        $this->assertSame(Language::English, Language::from('en-US'));
    }

    public function test_from_invalid_throws_value_error(): void
    {
        $this->expectException(\ValueError::class);
        Language::from('xx-XX');
    }
}
