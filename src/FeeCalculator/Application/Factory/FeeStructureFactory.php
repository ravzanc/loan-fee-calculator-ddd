<?php

declare(strict_types=1);

namespace App\FeeCalculator\Application\Factory;

use App\FeeCalculator\Application\Exception\LoanTermException;
use App\FeeCalculator\Domain\DTO\FeeStructureInterface;
use App\FeeCalculator\Domain\DTO\TwelveMonthFeeStructure;
use App\FeeCalculator\Domain\DTO\TwentyFourMonthFeeStructure;
use App\FeeCalculator\Domain\Enum\LoanTerm;

/**
 * A factory class to create the right FeeStructureInterface instance
 * based on the provided LoanTerm enum.
 */
final class FeeStructureFactory
{
    /**
     * @throws LoanTermException
     */
    public static function create(LoanTerm $term): FeeStructureInterface
    {
        return match ($term) {
            LoanTerm::TWELVE_MONTHS => new TwelveMonthFeeStructure(),
            LoanTerm::TWENTY_FOUR_MONTHS => new TwentyFourMonthFeeStructure(),
            default => throw new LoanTermException("Unsupported LoanTerm value provided."),
        };
    }
}