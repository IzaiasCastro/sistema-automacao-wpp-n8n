<?php

namespace App\Permissions;

enum AgendaPermissions: string
{
    use Permissions;

    case ViewAny = 'view-any Agenda';
    case View = 'view Agenda';
    case Create = 'create Agenda';
    case Update = 'update Agenda';
    case Delete = 'delete Agenda';
    case ForceDelete = 'force delete Agenda';
    case Restore = 'restore Agenda';

}
