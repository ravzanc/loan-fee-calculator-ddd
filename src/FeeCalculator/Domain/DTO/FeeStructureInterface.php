<?php

declare(strict_types=1);

namespace App\FeeCalculator\Domain\DTO;

/**
 * An interface for a fee structure DTO, ensuring all concrete
 * fee structures have a consistent way to provide their data.
 */
interface FeeStructureInterface
{
    /**
     * An array of breakpoints where the key is the amount and the value is the fee.
     *
     * @return array<int, int>
     */
    public function getBreakpoints(): array;
}