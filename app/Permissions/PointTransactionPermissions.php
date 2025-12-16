<?php

namespace App\Permissions;

enum PointTransactionPermissions: string
{
    use Permissions;

    case ViewAny = 'view-any PointTransaction';
    case View = 'view PointTransaction';
    case Create = 'create PointTransaction';
    case Update = 'update PointTransaction';
    case Delete = 'delete PointTransaction';
    case ForceDelete = 'force delete PointTransaction';
    case Restore = 'restore PointTransaction';

}
