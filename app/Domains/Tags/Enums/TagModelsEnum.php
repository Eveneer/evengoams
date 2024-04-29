<?php 

declare(strict_types=1);

namespace App\Domains\Tags\Enums;

use App\Domains\Transactions\Transaction;
use App\Domains\Vendors\Vendor;
use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

#[Description('Types of models for tags')]
final class TagModelsEnum extends Enum
{
    #[Description('Tags for Vendor')]
    public const VENDOR = Vendor::class;

    #[Description('Tags for Transactions')]
    public const TRANSACTION = Transaction::class;
}
