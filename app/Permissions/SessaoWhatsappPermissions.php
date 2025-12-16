<?php

namespace App\Permissions;

enum SessaoWhatsappPermissions: string
{
    use Permissions;

    case ViewAny = 'view-any SessaoWhatsapp';
    case View = 'view SessaoWhatsapp';
    case Create = 'create SessaoWhatsapp';
    case Update = 'update SessaoWhatsapp';
    case Delete = 'delete SessaoWhatsapp';
    case ForceDelete = 'force delete SessaoWhatsapp';
    case Restore = 'restore SessaoWhatsapp';

}
