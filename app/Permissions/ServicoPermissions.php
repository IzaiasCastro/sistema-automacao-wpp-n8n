<?php

namespace App\Permissions;

enum ServicoPermissions: string
{
    use Permissions;

    case ViewAny = 'view-any Servico';
    case View = 'view Servico';
    case Create = 'create Servico';
    case Update = 'update Servico';
    case Delete = 'delete Servico';
    case ForceDelete = 'force delete Servico';
    case Restore = 'restore Servico';

}
