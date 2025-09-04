<?php

declare(strict_types=1);

namespace App\Tests\Unit\FeeCalculator\Factory;

use Generator;
use App\FeeCalculator\Application\Factory\FeeStructureFactory;
use App\FeeCalculator\Domain\DTO\FeeStructureInterface;
use App\FeeCalculator\Domain\Enum\LoanTerm;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Ensure that the factory correctly provides the fee
 * structure based on the loan term.
 */
final class FeeStructureFactoryTest extends TestCase
{
    /**
     * Provide valid loan terms.
     *
     * @return Generator<string, array{LoanTerm}>
     */
    public static function validTermDataProvider(): Generator
    {
        yield 'Valid 12-month term' => [LoanTerm::TWELVE_MONTHS];
        yield 'Valid 24-month term' => [LoanTerm::TWENTY_FOUR_MONTHS];
    }

    /**
     * @param LoanTerm $term The loan term to test.
     */
    #[DataProvider('validTermDataProvider')]
    public function testGetFeeStructureForValidTerm(LoanTerm $term): void
    {
        $feeStructure = FeeStructureFactory::create($term);
        $this->assertInstanceOf(FeeStructureInterface::class, $feeStructure);

        // Assert that the fee breakpoints are an array with integer keys and values.
        $breakpoints = $feeStructure->getBreakpoints();
        $this->assertIsArray($breakpoints);
        foreach ($breakpoints as $key => $value) {
            $this->assertIsInt($key);
            $this->assertIsInt($value);
        }
    }
}
