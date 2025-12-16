<?php

namespace App\Permissions;

enum RewardPermissions: string
{
    use Permissions;

    case ViewAny = 'view-any Reward';
    case View = 'view Reward';
    case Create = 'create Reward';
    case Update = 'update Reward';
    case Delete = 'delete Reward';
    case ForceDelete = 'force delete Reward';
    case Restore = 'restore Reward';

}
