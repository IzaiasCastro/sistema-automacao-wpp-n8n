<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PointTransactionResource\Pages;
use App\Filament\Resources\PointTransactionResource\RelationManagers;
use App\Models\PointTransaction;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PointTransactionResource extends Resource
{
    protected static ?string $model = PointTransaction::class;

    protected static ?string $navigationGroup = 'Fidelização';
    protected static ?string $navigationIcon = 'heroicon-o-star';

    //mudar nome pra pt
    protected static ?string $navigationLabel = 'Transaçãos de Pontos';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('cliente_id')
                 ->relationship(
                            name: 'cliente',
                            titleAttribute: 'nome',
                            modifyQueryUsing: fn ($query) => $query->whereBelongsTo(Filament::getTenant())
                        )
                ->required(),
            Forms\Components\Select::make('type')
                ->options([
                    'earn' => 'Ganho',
                    'redeem' => 'Resgate',
                ])->required(),
            Forms\Components\TextInput::make('points')->numeric()->required(),
            Forms\Components\TextInput::make('description'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('cliente.nome')->label('Cliente'),
                Tables\Columns\BadgeColumn::make('type')
                    ->colors(['success' => 'earn', 'danger' => 'redeem']),
                Tables\Columns\TextColumn::make('points')->sortable(),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('created_at')->dateTime('d/m/Y H:i'),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListPointTransactions::route('/'),
            'create' => Pages\CreatePointTransaction::route('/create'),
            'edit' => Pages\EditPointTransaction::route('/{record}/edit'),
        ];
    }
}
