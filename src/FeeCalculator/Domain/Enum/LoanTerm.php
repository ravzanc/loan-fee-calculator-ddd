<?php

declare(strict_types=1);

namespace App\FeeCalculator\Domain\Enum;

/**
 * An enum to represent the supported loan terms in months.
 */
enum LoanTerm: int
{
    case TWELVE_MONTHS = 12;
    case TWENTY_FOUR_MONTHS = 24;

    /**
     * Implode enum values into a string value using custom separator
     *
     * @param string $separator
     * @return string
     */
    public static function implodeTerms(string $separator = ' or '): string
    {
        return implode($separator, array_map(static fn(self $item) => $item->value, self::cases()));
    }
}