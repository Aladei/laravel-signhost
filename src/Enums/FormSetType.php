<?php

namespace Noardcode\LaravelSignhost\Enums;

/**
 * Enum FormSetType
 *
 * Represents the supported form element types that can be applied
 * to a document (e.g., signature fields, checkboxes).
 *
 * @enum FormSetType
 */
enum FormSetType: string
{
    case Seal = 'Seal';
    case Signature = 'Signature';
    case Check = 'Check';
    case SingleLine = 'SingleLine';
}
