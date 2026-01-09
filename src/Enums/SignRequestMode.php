<?php

namespace Noardcode\LaravelSignhost\Enums;

/**
 * Enum SignRequestMode
 *
 * Defines how sign requests are sent to signers
 * (all at once or sequentially).
 *
 * @enum SignRequestMode
 */
enum SignRequestMode: int
{
    case None = 0;
    case AtOnce = 1;
    case Sequential = 2;
}
