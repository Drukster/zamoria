<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TelegramUserResource\Pages;
use App\Models\TelegramUser;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TelegramUserResource extends Resource
{
    protected static ?string $model = TelegramUser::class;
    protected static ?string $navigationGroup = 'Пользователи';
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $pluralLabel = 'Пользователи';

    public static function table(
        Table $table
    ): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->translateLabel()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('first_name')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('last_name')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('username')
                    ->translateLabel(),
                Tables\Columns\IconColumn::make('is_active')
                    ->translateLabel(),
                Tables\Columns\IconColumn::make('is_premium')
                    ->translateLabel(),
                Tables\Columns\TextColumn::make('created_at')
                    ->translateLabel()
                    ->date(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\DeleteAction::make()
                        ->modalHeading(__('telegram.users.delete'))
                ])
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTelegramUsers::route('/'),
            'create' => Pages\CreateTelegramUser::route('/create'),
            'edit' => Pages\EditTelegramUser::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
