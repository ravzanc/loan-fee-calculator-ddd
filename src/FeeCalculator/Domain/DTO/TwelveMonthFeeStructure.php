<?php

declare(strict_types=1);

namespace App\FeeCalculator\Domain\DTO;

use App\FeeCalculator\Domain\Enum\LoanTermBreakpoint;

/**
 * A concrete DTO for the 12-month loan fee structure.
 * This class holds only the configuration data for this specific term.
 */
final readonly class TwelveMonthFeeStructure implements FeeStructureInterface
{
    public function getBreakpoints(): array
    {
        return [
            LoanTermBreakpoint::ONE_THOUSAND->value => 50,
            LoanTermBreakpoint::TWO_THOUSAND->value => 90,
            LoanTermBreakpoint::THREE_THOUSAND->value => 90,
            LoanTermBreakpoint::FOUR_THOUSAND->value => 115,
            LoanTermBreakpoint::FIVE_THOUSAND->value => 100,
            LoanTermBreakpoint::SIX_THOUSAND->value => 120,
            LoanTermBreakpoint::SEVEN_THOUSAND->value => 140,
            LoanTermBreakpoint::EIGHT_THOUSAND->value => 160,
            LoanTermBreakpoint::NINE_THOUSAND->value => 180,
            LoanTermBreakpoint::TEN_THOUSAND->value => 200,
            LoanTermBreakpoint::ELEVEN_THOUSAND->value => 220,
            LoanTermBreakpoint::TWELVE_THOUSAND->value => 240,
            LoanTermBreakpoint::THIRTEEN_THOUSAND->value => 260,
            LoanTermBreakpoint::FOURTEEN_THOUSAND->value => 280,
            LoanTermBreakpoint::FIFTEEN_THOUSAND->value => 300,
            LoanTermBreakpoint::SIXTEEN_THOUSAND->value => 320,
            LoanTermBreakpoint::SEVENTEEN_THOUSAND->value => 340,
            LoanTermBreakpoint::EIGHTEEN_THOUSAND->value => 360,
            LoanTermBreakpoint::NINETEEN_THOUSAND->value => 380,
            LoanTermBreakpoint::TWENTY_THOUSAND->value => 400,
        ];
    }
}