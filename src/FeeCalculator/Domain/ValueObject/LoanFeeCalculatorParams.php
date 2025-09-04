<?php

declare(strict_types=1);

namespace App\FeeCalculator\Domain\ValueObject;

use App\FeeCalculator\Application\Exception\LoanTermBreakpointException;
use App\FeeCalculator\Application\Exception\LoanTermException;
use App\FeeCalculator\Domain\Enum\LoanTerm;
use App\FeeCalculator\Domain\Enum\LoanTermBreakpoint;
use ValueError;

/**
 * A ValueObject to encapsulate and validate the loan details.
 * This class ensures the data is always in a valid state.
 */
final readonly class LoanFeeCalculatorParams
{
    public Monetary $amount;
    public LoanTerm $term;

    /**
     * @throws LoanTermException
     * @throws LoanTermBreakpointException
     */
    public function __construct(string $amountString, string $termString)
    {
        $this->amount = Monetary::fromString($amountString);
        try {
            $this->term = LoanTerm::from((int)$termString);
        } catch (ValueError $e) {
            throw new LoanTermException($e->getMessage());
        }

        // Validate that the parsed amount is within the valid range.
        $minAmount = LoanTermBreakpoint::min();
        $maxAmount = LoanTermBreakpoint::max();
        if ($this->amount->getValue() < $minAmount || $this->amount->getValue() > $maxAmount) {
            throw new LoanTermBreakpointException('Loan amount is out of the defined range');
        }
    }
}