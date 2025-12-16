<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class GuiaRapido extends Page
{
    
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.guia-rapido';

    // Define o título no menu lateral
    protected static ?string $navigationLabel = 'Guia Rápido';

    // Define o grupo de navegação
    protected static ?string $navigationGroup = 'Configuração Inicial';

    // Opcional: define a posição no grupo (0 para ser o primeiro)
    protected static ?int $navigationSort = 0;
}
