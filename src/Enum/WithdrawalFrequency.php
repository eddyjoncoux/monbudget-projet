<?php

namespace App\Enum;

enum WithdrawalFrequency: string
{
    case DAILY = 'daily';
    case WEEKLY = 'weekly';
    case BIWEEKLY = 'biweekly';
    case MONTHLY = 'monthly';
    case QUARTERLY = 'quarterly';
    case ANNUAL = 'annual';

    public function label(): string
    {
        return match ($this) {
            self::DAILY => 'Quotidien',
            self::WEEKLY => 'Hebdomadaire',
            self::BIWEEKLY => 'Bi-hebdomadaire',
            self::MONTHLY => 'Mensuel',
            self::QUARTERLY => 'Trimestriel',
            self::ANNUAL => 'Annuel',
        };
    }

    /**
     * Calculate the next withdrawal date based on the frequency
     */
    public function getNextDate(\DateTimeImmutable $currentDate): \DateTimeImmutable
    {
        return match ($this) {
            self::DAILY => $currentDate->modify('+1 day'),
            self::WEEKLY => $currentDate->modify('+1 week'),
            self::BIWEEKLY => $currentDate->modify('+2 weeks'),
            self::MONTHLY => $currentDate->modify('+1 month'),
            self::QUARTERLY => $currentDate->modify('+3 months'),
            self::ANNUAL => $currentDate->modify('+1 year'),
        };
    }
}
