<?php 

declare(strict_types=1);

namespace App\Domains\Transactions\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

#[Description('Types of transactions')]
final class TransactionTypesEnum extends Enum
{
    #[Description('Transaction leading to increase in account balance')]
    public const INCOME = 'income';
    
    #[Description('Transaction leading to decrease in account balance')]
    public const EXPENSE = 'expense';

    #[Description('Transaction leading to overall amount in all accounts to be the same')]
    public const TRANSFER = 'transfer';
}
