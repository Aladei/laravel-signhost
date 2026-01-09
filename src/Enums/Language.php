<?php

namespace Noardcode\LaravelSignhost\Enums;

/**
 * Enum Language
 *
 * Represents the supported UI/communication languages for Signhost
 * transactions and notifications.
 *
 * @enum Language
 */
enum Language: string
{
    case Dutch = 'nl-NL';
    case English = 'en-US';
    case French = 'fr-FR';
    case German = 'de-DE';
    case Italian = 'it-IT';
    case Polish = 'pl-PL';
    case Spanish = 'es-ES';

    /**
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::Dutch => 'Dutch',
            self::English => 'English',
            self::French => 'French',
            self::German => 'German',
            self::Italian => 'Italian',
            self::Polish => 'Polish',
            self::Spanish => 'Spanish',
        };
    }
}
