<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SessaoWhatsappResource\Pages;
use App\Filament\Resources\SessaoWhatsappResource\RelationManagers;
use App\Models\SessaoWhatsapp;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SessaoWhatsappResource extends Resource
{
    protected static ?string $model = SessaoWhatsapp::class;

    protected static ?string $navigationGroup = 'Integrações';
    protected static ?string $navigationIcon = 'heroicon-s-chat-bubble-left';

    protected static ?string $navigationLabel = 'Whatsapp';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('webhook')
            ->label('URL do Webhook')
            ->url()
            ->default(function () {
                $user = User::find(auth()->id());
                if($user->organization->count() > 1) return null;
                // 🔹 Busca a última sessão criada (ou a ativa)
                        $ultimaSessao = SessaoWhatsapp::where('organization_id', $user->organization->first()->id)->latest('id')->first();
                        return $ultimaSessao?->webhook ?? null;
                    })
                    ->disabled() // 🔒 deixa o campo bloqueado
                    ->dehydrated(true) // garante que o valor ainda é salvo
                    ->nullable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('session_name')->label('Sessão'),
                Tables\Columns\BooleanColumn::make('active')->label('Ativa'),
            ])
            ->actions([
                Tables\Actions\Action::make('viewQr')
                        ->label('Conectar')
                        ->icon('heroicon-o-qr-code')
                        ->color('warning')
                        ->visible(fn (SessaoWhatsapp $record) => !$record->active) // 👈 só mostra se NÃO estiver ativa
                        ->modalHeading(fn (SessaoWhatsapp $record) => "QR Code da Sessão: {$record->session_name}")
                        ->modalContent(function (SessaoWhatsapp $record) {
                            $data = $record->generateSession($record); // chama seu método que retorna o QR Code
                            // dd($data);
                            if (!empty($data['qrCode'])) {
                                // Gera o HTML do QR Code direto
                                return view('filament.qrcode-page', [
                                    'qrcode' => $data['qrCode'],
                                    'sessionName' => $record->session_name,
                                ]);
                            }

                        // Caso não tenha QR Code, mostra mensagem simples no modal
                        return view('filament.qrcode-page', [
                            'qrcode' => null,
                            'message' => 'QR Code não disponível no momento.',
                        ]);
                    })
                    ->modalSubmitAction(false) // remove botão "Salvar"
                    ->requiresConfirmation(false) // abre direto, sem confirmação

            ])
            ->filters([
                //
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSessaoWhatsapps::route('/'),
            'create' => Pages\CreateSessaoWhatsapp::route('/create'),
            'edit' => Pages\EditSessaoWhatsapp::route('/{record}/edit'),
        ];
    }
}
