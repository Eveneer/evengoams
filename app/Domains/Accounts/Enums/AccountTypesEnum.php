<?php 

declare(strict_types=1);

namespace App\Domains\Accounts\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

#[Description('Account types')]
final class AccountTypesEnum extends Enum
{
    #[Description('Cash Account')]
    public const CASH = 'Cash';

    #[Description('Bank Account')]
    public const BANK = 'Bank';

    #[Description('Mobile Financial Account')]
    public const MOBILE = 'Mobile';
}
