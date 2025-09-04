<?php

declare(strict_types=1);

namespace App\FeeCalculator\Domain\DTO;

use App\FeeCalculator\Domain\Enum\LoanTermBreakpoint;

/**
 * A concrete DTO for the 24-month loan fee structure.
 * This class holds only the configuration data for this specific term.
 */
final readonly class TwentyFourMonthFeeStructure implements FeeStructureInterface
{
    public function getBreakpoints(): array
    {
        return [
            LoanTermBreakpoint::ONE_THOUSAND->value => 70,
            LoanTermBreakpoint::TWO_THOUSAND->value => 100,
            LoanTermBreakpoint::THREE_THOUSAND->value => 120,
            LoanTermBreakpoint::FOUR_THOUSAND->value => 160,
            LoanTermBreakpoint::FIVE_THOUSAND->value => 200,
            LoanTermBreakpoint::SIX_THOUSAND->value => 240,
            LoanTermBreakpoint::SEVEN_THOUSAND->value => 280,
            LoanTermBreakpoint::EIGHT_THOUSAND->value => 320,
            LoanTermBreakpoint::NINE_THOUSAND->value => 360,
            LoanTermBreakpoint::TEN_THOUSAND->value => 400,
            LoanTermBreakpoint::ELEVEN_THOUSAND->value => 440,
            LoanTermBreakpoint::TWELVE_THOUSAND->value => 480,
            LoanTermBreakpoint::THIRTEEN_THOUSAND->value => 520,
            LoanTermBreakpoint::FOURTEEN_THOUSAND->value => 560,
            LoanTermBreakpoint::FIFTEEN_THOUSAND->value => 600,
            LoanTermBreakpoint::SIXTEEN_THOUSAND->value => 640,
            LoanTermBreakpoint::SEVENTEEN_THOUSAND->value => 680,
            LoanTermBreakpoint::EIGHTEEN_THOUSAND->value => 720,
            LoanTermBreakpoint::NINETEEN_THOUSAND->value => 760,
            LoanTermBreakpoint::TWENTY_THOUSAND->value => 800,
        ];
    }
}