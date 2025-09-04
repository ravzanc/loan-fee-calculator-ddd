<?php

declare(strict_types=1);

namespace App\Tests\Unit\FeeCalculator\ValueObject;

use Generator;
use App\FeeCalculator\Application\Exception\LoanTermBreakpointException;
use App\FeeCalculator\Application\Exception\LoanTermException;
use App\FeeCalculator\Domain\ValueObject\LoanFeeCalculatorParams;
use App\FeeCalculator\Domain\ValueObject\Monetary;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Ensure that the data is correctly encapsulated and validated.
 */
final class LoanFeeCalculatorParamsTest extends TestCase
{
    /**
     * Provide valid LoanFeeCalculatorParams inputs.
     *
     * @return Generator<string, array{string, string}>
     */
    public static function validLoanFeeCalculatorParamsDataProvider(): Generator
    {
        yield 'Valid minimum amount and term' => ['1000', '12'];
        yield 'Valid maximum amount and term' => ['20,000', '24'];
        yield 'Valid mid-range amount' => ['15,000.00', '12'];
    }

    /**
     * Tests that a LoanTermBreakpointException is thrown when the loan amount
     * is outside the defined range.
     */
    public function testInvalidAmountThrowsException(): void
    {
        $this->expectException(LoanTermBreakpointException::class);
        // This amount is below the minimum allowed breakpoint (1000).
        new LoanFeeCalculatorParams('999.00', '12');
    }

    /**
     * Tests that a LoanTermException is thrown when an unsupported loan term
     * is provided.
     */
    public function testInvalidTermThrowsException(): void
    {
        $this->expectException(LoanTermException::class);
        // The term '18' is not supported; only 12 and 24 are valid.
        new LoanFeeCalculatorParams('1000.00', '18');
    }

    /**
     * @param string $amountString The loan amount string.
     * @param string $termString The loan term string.
     */
    #[DataProvider('validLoanFeeCalculatorParamsDataProvider')]
    public function testLoanFeeCalculatorParamsIsCreatedCorrectly(string $amountString, string $termString): void
    {
        $params = new LoanFeeCalculatorParams($amountString, $termString);

        $this->assertEquals(Monetary::fromString($amountString)->getValue(), $params->amount->getValue());
        $this->assertEquals((int)$termString, $params->term->value);
    }
}
