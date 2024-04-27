<?php 

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

#[Description('Types of vendors')]
final class VendorTypesEnum extends Enum
{
    #[Description('Vendor provides services')]
    public const SERVICE = 'service';

    #[Description('Vendor provides products')]
    public const PRODUCT = 'product';
}
