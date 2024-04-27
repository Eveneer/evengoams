<?php 

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

#[Description('Types of models for tags')]
final class TagModelsEnum extends Enum
{
    #[Description('Tags for Vendor')]
    public const VENDOR = 'tag';

    #[Description('Tags for Transactions')]
    public const TRANSACTION = 'tag';
}
