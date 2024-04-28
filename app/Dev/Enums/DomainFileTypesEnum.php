<?php 

declare(strict_types=1);

namespace App\Dev\Enums;

use BenSampo\Enum\Enum;
use BenSampo\Enum\Attributes\Description;

#[Description('Different file types of dev tools')]
final class DomainFileTypesEnum extends Enum
{
    #[Description('Action which creates an instance of the domain model')]
    public const CREATE_ACTION = 'create-action';

    #[Description('Action which updates an instance of the domain model')]
    public const UPDATE_ACTION = 'update-action';

    #[Description('Action which trashes an instance of the domain model')]
    public const TRASH_ACTION = 'trash-action';

    #[Description('Action which restores an instance of the domain model')]
    public const RESTORE_ACTION = 'restore-action';

    #[Description('Action which deletes a trashed instance of the domain model')]
    public const DELETE_ACTION = 'delete-action';

    #[Description('A generic action file')]
    public const ACTION = 'action';

    #[Description('An enum for the domain')]
    public const ENUM = 'enum';

    #[Description('The model of the domain')]
    public const MODEL = 'model';
}
