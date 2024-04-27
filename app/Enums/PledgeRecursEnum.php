<?php 

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

#[Description('Recurrence rates of pledges')]
final class PledgeRecursEnum extends Enum
{
    #[Description('Does not recur')]
    public const NONE = 'none';

    #[Description('Recurs every week')]
    public const WEEKLY = 'weekly';

    #[Description('Recurs every two weeks')]
    public const BIWEEKLY = 'bi-weekly';

    #[Description('Recurs every month')]
    public const MONTHLY = 'monthly';

    #[Description('Recurs every year')]
    public const YEARLY = 'yearly';    
}
