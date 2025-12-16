<?php

namespace App\Permissions;

enum ProfissionalPermissions: string
{
    use Permissions;

    case ViewAny = 'view-any Profissional';
    case View = 'view Profissional';
    case Create = 'create Profissional';
    case Update = 'update Profissional';
    case Delete = 'delete Profissional';
    case ForceDelete = 'force delete Profissional';
    case Restore = 'restore Profissional';

}
