<?php

declare(strict_types=1);

namespace App\FeeCalculator\Application\Service;

use App\FeeCalculator\Application\Exception\LoanTermBreakpointException;
use App\FeeCalculator\Application\Exception\LoanTermException;
use App\FeeCalculator\Application\Factory\FeeStructureFactory;
use App\FeeCalculator\Domain\Service\FeeInterpolator;
use App\FeeCalculator\Domain\ValueObject\LoanFeeCalculatorParams;
use App\FeeCalculator\Domain\ValueObject\Monetary;

/**
 * Loan fee calculator configured with injected params
 * It encapsulates the fee calculation logic:
 * - linear interpolation
 * - rounding up to nearest 5
 */
final readonly class LoanFeeCalculator
{
    private LoanFeeCalculatorParams $loanFeeCalculatorParams;

    /**
     * @param LoanFeeCalculatorParams $loanFeeCalculatorParams A ValueObject containing the validated calculator params.
     */
    public function __construct(LoanFeeCalculatorParams $loanFeeCalculatorParams)
    {
        $this->loanFeeCalculatorParams = $loanFeeCalculatorParams;
    }

    /**
     * Calculates the fee using loan amount and term injected params.
     *
     * @return Monetary The calculated fee as a Monetary value object.
     * @throws LoanTermBreakpointException|LoanTermException
     */
    public function calculateFee(): Monetary
    {
        // Use the factory to create the specific fee structure for this calculation.
        $feeStructure = FeeStructureFactory::create($this->loanFeeCalculatorParams->term);

        // Use the FeeInterpolator service to fetch the interpolated fee within fee structure breakpoints.
        $breakpoints = $feeStructure->getBreakpoints();
        $interpolatedFee = FeeInterpolator::interpolateFee($this->loanFeeCalculatorParams->amount->getValue(), $breakpoints);

        // Calculate the total amount with the fee
        $total = $this->loanFeeCalculatorParams->amount->getValue() + $interpolatedFee;
        // Round the total up to the nearest 5.
        $roundedTotal = ceil($total / 5) * 5;

        // The final fee is the difference between the rounded total and the original amount.
        $finalFee = $roundedTotal - $this->loanFeeCalculatorParams->amount->getValue();

        // Return the fee as a Monetary object.
        return new Monetary($finalFee);
    }
}
