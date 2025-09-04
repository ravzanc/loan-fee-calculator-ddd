<?php

declare(strict_types=1);

namespace App\FeeCalculator\Domain\Service;

use App\FeeCalculator\Application\Exception\LoanTermBreakpointException;

/**
 * A service utility class to handle linear interpolation logic.
 */
final class FeeInterpolator
{
    /**
     * Interpolates the fee based on a given amount and fee breakpoints.
     *
     * @param float $amount The loan amount to interpolate.
     * @param array<int, int> $breakpoints An associative array of amount => fee.
     * @return float The interpolated fee.
     * @throws LoanTermBreakpointException If the amount is outside the defined breakpoints range.
     */
    public static function interpolateFee(float $amount, array $breakpoints): float
    {
        $amounts = array_keys($breakpoints);
        $fees = array_values($breakpoints);

        // Find the lower and upper bounds for linear interpolation.
        $lowerIndex = null;
        $upperIndex = null;

        foreach ($amounts as $index => $currentAmount) {
            if ($amount >= $currentAmount) {
                $lowerIndex = $index;
                if ($amount === (float)$currentAmount) {
                    // If the amount is an exact breakpoint, we've found our match.
                    $upperIndex = $index;
                    break;
                }
            } else {
                // The current amount is greater, so the previous one is the lower bound,
                // and this one is the upper bound.
                $upperIndex = $index;
                break;
            }
        }

        // Check if a valid range was found.
        if ($lowerIndex === null || ($amount > end($amounts) && $upperIndex === null)) {
            throw new LoanTermBreakpointException("Loan amount is out of the defined range");
        }

        $lowerAmount = $amounts[$lowerIndex];
        $lowerFee = $fees[$lowerIndex];

        // If the amount is an exact breakpoint or exceeds the max breakpoint, no interpolation is needed.
        if ($lowerIndex === $upperIndex || $upperIndex === null) {
            return (float)$lowerFee;
        }

        $upperAmount = $amounts[$upperIndex];
        $upperFee = $fees[$upperIndex];

        // Perform linear interpolation.
        return (float)$lowerFee +
            ($amount - $lowerAmount) *
            ($upperFee - $lowerFee) /
            ($upperAmount - $lowerAmount);
    }
}