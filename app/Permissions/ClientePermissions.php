<?php

namespace App\Permissions;

enum ClientePermissions: string
{
    use Permissions;

    case ViewAny = 'view-any Cliente';
    case View = 'view Cliente';
    case Create = 'create Cliente';
    case Update = 'update Cliente';
    case Delete = 'delete Cliente';
    case ForceDelete = 'force delete Cliente';
    case Restore = 'restore Cliente';

}
