<?php

namespace App\Tests\Unit\FeeCalculator\Service;

use Generator;
use App\FeeCalculator\Application\Exception\LoanTermBreakpointException;
use App\FeeCalculator\Domain\Service\FeeInterpolator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Ensure that the linear interpolation logic for fees works as expected.
 */
final class FeeInterpolatorTest extends TestCase
{
    /**
     * Provide various loan amount, fee breakpoints, and expected interpolated fees.
     *
     * @return Generator<string, array{float, array<int, int>, float}>
     */
    public static function interpolationDataProvider(): Generator
    {
        // Example breakpoints for 12 months, simplified for this test.
        $breakpoints12Months = [
            1000 => 50,
            5000 => 100,
            10000 => 200,
            20000 => 400,
        ];

        // Example breakpoints for 24 months, simplified for this test.
        $breakpoints24Months = [
            1000 => 70,
            5000 => 200,
            10000 => 400,
            20000 => 800,
        ];

        // Test an exact breakpoint.
        yield 'Exact breakpoint: 5000.00 / 12 months' => [5000.00, $breakpoints12Months, 100.00];

        // Test another exact breakpoint.
        yield 'Exact breakpoint: 10000.00 / 24 months' => [10000.00, $breakpoints24Months, 400.00];

        // Test linear interpolation between breakpoints for 12 months.
        // Amount is 3000, which is halfway between 1000 and 5000.
        // Fee for 1000 is 50, fee for 5000 is 100.
        // Expected interpolated fee: 50 + (3000 - 1000) * (100 - 50) / (5000 - 1000) = 75
        yield 'Interpolation: 3000.00 / 12 months' => [3000.00, $breakpoints12Months, 75.00];

        // Test linear interpolation between breakpoints for 24 months.
        // Amount is 7500, which is halfway between 5000 and 10000.
        // Fee for 5000 is 200, fee for 10000 is 400.
        // Expected interpolated fee: 200 + (7500 - 5000) * (400 - 200) / (10000 - 5000) = 300
        yield 'Interpolation: 7500.00 / 24 months' => [7500.00, $breakpoints24Months, 300.00];
    }

    /**
     * @param float $amount The loan amount.
     * @param array<int, int> $breakpoints The fee breakpoints.
     * @param float $expectedFee The expected fee value.
     */
    #[DataProvider('interpolationDataProvider')]
    public function testInterpolationCalculatesCorrectFee(float $amount, array $breakpoints, float $expectedFee): void
    {
        $this->assertEqualsWithDelta($expectedFee, FeeInterpolator::interpolateFee($amount, $breakpoints), 0.000001);
    }

    /**
     * Tests that an exception is thrown when the amount is outside the defined breakpoints.
     */
    public function testThrowsExceptionForInvalidAmount(): void
    {
        $this->expectException(LoanTermBreakpointException::class);
        $breakpoints = [
            1000 => 50,
            5000 => 100,
        ];
        // Test an amount below the minimum breakpoint.
        FeeInterpolator::interpolateFee(999.00, $breakpoints);

        $this->expectException(LoanTermBreakpointException::class);
        // Test an amount above the maximum breakpoint.
        FeeInterpolator::interpolateFee(5001.00, $breakpoints);
    }
}
