<?php

declare(strict_types=1);

namespace App\FeeCalculator\Application\Exception;

use Exception;
use App\FeeCalculator\Domain\Enum\LoanTerm;

/**
 * A custom exception for loan term validation errors.
 */
final class LoanTermException extends Exception
{
    public function __construct(string $message)
    {
        parent::__construct(
            $message . sprintf(" : Term must be %s months", LoanTerm::implodeTerms())
        );
    }
}