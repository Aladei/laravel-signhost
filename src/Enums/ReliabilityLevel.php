<?php

namespace Noardcode\LaravelSignhost\Enums;

/**
 * Enum ReliabilityLevel
 *
 * Describes the assurance level assigned to an identity verification
 * result (as reported by identity providers).
 *
 * @enum ReliabilityLevel
 */
enum ReliabilityLevel: string
{
    case Basic = 'Basis';
    case Medium = 'Midden';
    case Substantial = 'Substantieel';
    case High = 'Hoog';

    /**
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::Basic => 'Basic',
            self::Medium => 'Medium',
            self::Substantial => 'Substantial',
            self::High => 'High',
        };
    }
}
