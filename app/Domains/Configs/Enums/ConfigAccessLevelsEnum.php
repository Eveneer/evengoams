<?php 

declare(strict_types=1);

namespace App\Domains\Configs\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

#[Description('Access Levels of Configs')]
final class ConfigAccessLevelsEnum extends Enum
{
    #[Description('Config viewable and editable by platform admins and org admins')]
    public const PUBLC = 'Public';

    #[Description('Config viewable and editable by platform admins and only viewable by org admins')]
    public const PRIVATE = 'Private';

    #[Description('Config viewable and editable by platform admins only')]
    public const ADMIN = 'Admin';
}
