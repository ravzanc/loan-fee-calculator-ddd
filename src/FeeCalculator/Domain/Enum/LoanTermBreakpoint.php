<?php

declare(strict_types=1);

namespace App\FeeCalculator\Domain\Enum;

/**
 * An enum to represent the supported loan amountString breakpoints.
 */
enum LoanTermBreakpoint: int
{
    case ONE_THOUSAND = 1000;
    case TWO_THOUSAND = 2000;
    case THREE_THOUSAND = 3000;
    case FOUR_THOUSAND = 4000;
    case FIVE_THOUSAND = 5000;
    case SIX_THOUSAND = 6000;
    case SEVEN_THOUSAND = 7000;
    case EIGHT_THOUSAND = 8000;
    case NINE_THOUSAND = 9000;
    case TEN_THOUSAND = 10000;
    case ELEVEN_THOUSAND = 11000;
    case TWELVE_THOUSAND = 12000;
    case THIRTEEN_THOUSAND = 13000;
    case FOURTEEN_THOUSAND = 14000;
    case FIFTEEN_THOUSAND = 15000;
    case SIXTEEN_THOUSAND = 16000;
    case SEVENTEEN_THOUSAND = 17000;
    case EIGHTEEN_THOUSAND = 18000;
    case NINETEEN_THOUSAND = 19000;
    case TWENTY_THOUSAND = 20000;

    /**
     * Calculate the minimum breakpoint value
     *
     * @return int
     */
    public static function min(): int
    {
        return min(array_map(static fn(self $item) => $item->value, self::cases()));
    }

    /**
     * Calculate the maximum breakpoint value
     *
     * @return int
     */
    public static function max(): int
    {
        return max(array_map(static fn(self $item) => $item->value, self::cases()));
    }
}