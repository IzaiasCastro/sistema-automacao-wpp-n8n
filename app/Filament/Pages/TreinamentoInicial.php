<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Form;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;

use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;

use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;


class TreinamentoInicial extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static bool $shouldRegisterNavigation = false; 
    protected static string $view = 'filament.pages.treinamento-inicial';

    public ?array $data = [];

    protected function getFormSchema(): array
    {
        return [
            Wizard::make([
                Wizard\Step::make('Informações Básicas')
                    ->schema([
                        TextInput::make('nome_sistema')->required(),
                        Textarea::make('descricao'),
                    ]),

                Wizard\Step::make('Preferências')
                    ->schema([
                        Toggle::make('modo_manutencao'),
                        Toggle::make('envio_email'),
                    ]),

                Wizard\Step::make('Finalização')
                    ->schema([
                        TextInput::make('responsavel')->required(),
                        TextInput::make('email_responsavel')->email()->required(),
                        Checkbox::make('aceite_termos')->required(),
                    ]),
            ])
            // ->submitAction(fn() => $this->salvarConfiguracao()),
        ];
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->makeForm()
                ->schema($this->getFormSchema())
                ->statePath('data')
                ->model('array'),
        ];
    }

    public function salvarConfiguracao(): void
    {
        $data = $this->form->getState();

        Notification::make()
            ->title('Configuração realizada com sucesso!')
            ->success()
            ->send();
    }
}
