<?php 

declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

#[Description('Types of users')]
final class UserTypesEnum extends Enum
{
    #[Description('User is platform admin')]
    public const ADMIN = 'admin';

    #[Description('User is member of the organization')]
    public const ORG_MEMBER = 'org_member';

    #[Description('User is a donor')]
    public const DONOR = 'donor';
}
