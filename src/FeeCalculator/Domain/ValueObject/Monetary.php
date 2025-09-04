<?php

declare(strict_types=1);

namespace App\FeeCalculator\Domain\ValueObject;

/**
 * Value object for handling monetary values with precision.
 */
final readonly class Monetary
{
    /**
     * The number of cents in one unit of amount.
     */
    private const int CENTS_UNIT = 100;

    private int $valueInCents;

    /**
     * Constructs a Monetary object from a float or integer.
     * The value is internally converted to cents to maintain precision.
     *
     * @param float|int $value The monetary value.
     */
    public function __construct(float|int $value)
    {
        // Convert the value to cents and round to the nearest integer.
        // This ensures floating-point issues are handled at the entry point.
        $this->valueInCents = (int)round($value * self::CENTS_UNIT);
    }

    /**
     * Creates a Monetary object from a formatted string (e.g., "1,234.56").
     *
     * @param string $value The formatted monetary string.
     * @return self
     */
    public static function fromString(string $value): self
    {
        // Remove non-numeric characters except for the decimal point.
        $cleanedValue = str_replace([' ', ','], '', $value);

        return new self((float)$cleanedValue);
    }

    /**
     * Formats a given number as a string with two decimal places.
     *
     * @param mixed $number The number to format.
     * @return string
     */
    public static function formatValue(mixed $number): string
    {
        return new self((float)$number)->format();
    }

    /**
     * Formats the monetary value as a string with two decimal places.
     *
     * @return string
     */
    public function format(): string
    {
        return number_format($this->getValue(), 2);
    }

    /**
     * Gets the monetary value as a float.
     *
     * @return float
     */
    public function getValue(): float
    {
        return $this->valueInCents / self::CENTS_UNIT;
    }
}
