<?php

namespace App\Enum;
enum TransactionType: string
{
    case EXPENSE = 'expense';
    case INCOME = 'income';

    public function label(): string
    {
        return match ($this) {
            self::EXPENSE => 'Dépense',
            self::INCOME => 'Revenu',
        };
    }
}
