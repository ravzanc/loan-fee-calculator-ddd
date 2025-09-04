<?php

declare(strict_types=1);

namespace App\Tests\Unit\FeeCalculator\ValueObject;

use Generator;
use App\FeeCalculator\Domain\ValueObject\Monetary;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * Ensure that the Monetary value object handles values
 * with precision and formats them correctly.
 */
final class MonetaryTest extends TestCase
{
    /**
     * Provide a variety of values to test the constructor and formatting.
     *
     * @return Generator<string, array{float|int, float, string}>
     */
    public static function monetaryValueDataProvider(): Generator
    {
        // Value to test, expected float value, expected formatted string
        yield 'integer value' => [123, 123.00, '123.00'];
        yield 'simple float' => [123.45, 123.45, '123.45'];
        yield 'float requiring rounding up' => [123.456, 123.46, '123.46'];
        yield 'float requiring rounding down' => [123.454, 123.45, '123.45'];
        yield 'zero value' => [0, 0.00, '0.00'];
        yield 'negative value' => [-10.50, -10.50, '-10.50'];
    }

    /**
     * @param float|int $value The value to pass to the constructor.
     * @param float $expectedFloat The expected value from getValue().
     * @param string $expectedString The expected formatted string from format().
     */
    #[DataProvider('monetaryValueDataProvider')]
    public function testConstructorAndGetValue(float|int $value, float $expectedFloat, string $expectedString): void
    {
        $monetary = new Monetary($value);
        $this->assertEquals($expectedFloat, $monetary->getValue());
        $this->assertEquals($expectedString, $monetary->format());
    }

    /**
     * Tests the static fromString() method.
     */
    public function testFromString(): void
    {
        $monetary = Monetary::fromString('1,234.56');
        $this->assertEquals(1234.56, $monetary->getValue());

        $monetary = Monetary::fromString('1000.00');
        $this->assertEquals(1000.00, $monetary->getValue());

        $monetary = Monetary::fromString('1200');
        $this->assertEquals(1200.00, $monetary->getValue());
    }

    /**
     * Tests the static formatValue() method.
     */
    public function testFormatValue(): void
    {
        $this->assertEquals('123.46', Monetary::formatValue(123.456));
        $this->assertEquals('100.00', Monetary::formatValue(100));
        $this->assertEquals('0.00', Monetary::formatValue(0));
        $this->assertEquals('-50.25', Monetary::formatValue(-50.25));
    }
}
