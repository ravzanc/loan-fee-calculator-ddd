<?php

declare(strict_types=1);

namespace App\FeeCalculator\Application\Exception;

use Exception;
use App\FeeCalculator\Domain\Enum\LoanTermBreakpoint;
use App\FeeCalculator\Domain\ValueObject\Monetary;

/**
 * A custom exception for loan term breakpoint validation errors.
 */
final class LoanTermBreakpointException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct(
            $message . sprintf(
                " : Amount must be between %s and %s",
                Monetary::formatValue(LoanTermBreakpoint::min()),
                Monetary::formatValue(LoanTermBreakpoint::max())
            )
        );
    }
}