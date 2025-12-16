<?php

namespace App\Permissions;

enum AgendamentoPermissions: string
{
    use Permissions;

    case ViewAny = 'view-any Agendamento';
    case View = 'view Agendamento';
    case Create = 'create Agendamento';
    case Update = 'update Agendamento';
    case Delete = 'delete Agendamento';
    case ForceDelete = 'force delete Agendamento';
    case Restore = 'restore Agendamento';

}
