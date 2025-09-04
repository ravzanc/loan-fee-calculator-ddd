<?php

declare(strict_types=1);

namespace App\Tests\Feature\FeeCalculator;

use Generator;
use App\FeeCalculator\Application\Service\LoanFeeCalculator;
use App\FeeCalculator\Domain\ValueObject\LoanFeeCalculatorParams;
use App\FeeCalculator\Domain\ValueObject\Monetary;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Ensure that the fee calculation logic works as expected
 * under various conditions, including valid and invalid inputs.
 */
final class LoanFeeCalculatorTest extends TestCase
{
    /**
     * Provide various loan amount and term combinations
     * along with their expected calculated fees.
     *
     * @return Generator<string, array{string, string, float}>
     */
    public static function feeCalculationDataProvider(): Generator
    {
        // Test an exact breakpoint.
        yield 'Exact breakpoint: 5000.00 / 12 months' => ['5000.00', '12', 100.00];

        // Test another exact breakpoint.
        yield 'Exact breakpoint: 10000.00 / 24 months' => ['10000.00', '24', 400.00];

        // Test linear interpolation.
        // 11500 is halfway between 11000 and 12000.
        // 24-month fee for 11000 is 440, for 12000 is 480.
        // Expected interpolated fee is 460.
        yield 'Interpolation: 11500.00 / 24 months' => ['11500.00', '24', 460.00];

        // Test another linear interpolation with amount in currency format.
        // Amount is 19250, which is between 10000 and 20000.
        // 12-month fee for 10000 is 200, for 20000 is 400.
        // Expected interpolated fee: 200 + (19250 - 10000) * (400 - 200) / (20000 - 10000) = 385.00.
        // Total amount is 19250 + 385 = 19635, which is an exact multiple of 5.
        yield 'Interpolation: 19250.00 / 12 months' => ['19,250.00', '12', 385.00];

        // Test rounding logic with interpolation.
        // Interpolated fee for 11500.01 is 460.0004.
        // Total amount is 11500.01 + 460.0004 = 11960.0104.
        // Rounded up to nearest 5 is 11965.
        // Final fee is 11965 - 11500.01 = 464.99.
        yield 'Rounding with interpolation: 11500.01 / 24 months' => ['11500.01', '24', 464.99];
    }

    /**
     * @param string $amountString The loan amount to test.
     * @param string $termString The loan term to test.
     * @param float $expectedFeeValue The expected fee value.
     */
    #[DataProvider('feeCalculationDataProvider')]
    public function testCalculateFeeWithDataProvider(string $amountString, string $termString, float $expectedFeeValue): void
    {
        $loanFeeCalculatorParams = new LoanFeeCalculatorParams($amountString, $termString);
        $calculator = new LoanFeeCalculator($loanFeeCalculatorParams);
        $expectedFee = new Monetary($expectedFeeValue);

        $this->assertEquals($expectedFee->getValue(), $calculator->calculateFee()->getValue());
        $this->assertEquals($expectedFee->format(), $calculator->calculateFee()->format());
    }
}