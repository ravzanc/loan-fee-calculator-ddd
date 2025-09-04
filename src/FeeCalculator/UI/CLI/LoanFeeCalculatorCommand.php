<?php

declare(strict_types=1);

namespace App\FeeCalculator\UI\CLI;

use Exception;
use App\FeeCalculator\Application\Exception\LoanTermBreakpointException;
use App\FeeCalculator\Application\Exception\LoanTermException;
use App\FeeCalculator\Application\Service\LoanFeeCalculator;
use App\FeeCalculator\Domain\ValueObject\LoanFeeCalculatorParams;

/**
 * This command calculates a loan fee based on a given amount and term
 * Exit codes:
 * 0 - Success
 * 1 - Custom Error for Handled Exceptions
 * 2 - Error for Not Handled Exceptions
 */
final class LoanFeeCalculatorCommand
{
    public static function run(array $argv): void
    {
        $args = array_slice($argv, 1);

        // Validate command-line arguments.
        if (count($args) !== 2) {
            fwrite(STDERR, "Error: Invalid number of arguments.\n");
            fwrite(STDERR, "Usage: ./bin/calculate-fee <amount> <term>\n");
            fwrite(STDERR, "Example: ./bin/calculate-fee 11,500.00 24\n");
            exit(1);
        }

        // Extract amount and term values as strings from command-line arguments.
        [$amountString, $termString] = $args;

        try {
            $loanFeeCalculatorParams = new LoanFeeCalculatorParams($amountString, $termString);
            $calculator = new LoanFeeCalculator($loanFeeCalculatorParams);
            $fee = $calculator->calculateFee();

            // The fee is a Monetary object, so format it before displaying.
            echo $fee->format() . "\n";
            exit(0);

        } catch (LoanTermException|LoanTermBreakpointException $e) {
            // Handled Exceptions
            fwrite(STDERR, "Error: " . $e->getMessage() . "\n");
            exit(1);
        } catch (Exception $e) {
            // Not Handled Exceptions
            fwrite(STDERR, "An unexpected error occurred: " . $e->getMessage() . "\n");
            exit(2);
        }
    }
}