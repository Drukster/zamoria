<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TelegramUserResource\Pages;
use App\Models\TelegramUser;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TelegramUserResource extends Resource
{
    protected static ?string $model = TelegramUser::class;
    protected static ?string $navigationGroup = 'Пользователи';
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $pluralLabel = 'Пользователи';
    protected static ?string $label = 'телеграм пользователя';

    public static function infolist(
        Infolist $infolist
    ): Infolist
    {
        return $infolist
            ->schema([
                Grid::make()
                    ->columns(12)
                    ->schema([
                        Section::make('Основная информация')
                            ->columnSpan(8)
                            ->columns(2)
                            ->schema([
                                TextEntry::make('id')
                                    ->badge()
                                    ->translateLabel(),
                                TextEntry::make('telegram_id')
                                    ->badge()
                                    ->translateLabel(),
                                TextEntry::make('first_name')
                                    ->translateLabel(),
                                TextEntry::make('last_name')
                                    ->translateLabel(),
                                TextEntry::make('username')
                                    ->translateLabel(),
                            ]),
                        Section::make('Дополнительная информация')
                            ->columnSpan(4)
                            ->schema([
                                IconEntry::make('is_premium')
                                    ->translateLabel()
                                    ->alignCenter()
                                    ->trueIcon('heroicon-o-check-circle')
                                    ->falseIcon('heroicon-o-x-circle'),
                                IconEntry::make('is_active')
                                    ->translateLabel()
                                    ->alignCenter()
                                    ->trueIcon('heroicon-o-check-circle')
                                    ->falseIcon('heroicon-o-x-circle')
                            ])
                    ])
            ]);
    }

    public static function table(
        Table $table
    ): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->translateLabel()
                    ->disabledClick()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('telegram_id')
                    ->translateLabel()
                    ->badge()
                    ->disabledClick()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('first_name')
                    ->translateLabel()
                    ->disabledClick(),
                Tables\Columns\TextColumn::make('last_name')
                    ->translateLabel()
                    ->disabledClick(),
                Tables\Columns\TextColumn::make('username')
                    ->translateLabel()
                    ->disabledClick(),
                Tables\Columns\IconColumn::make('is_active')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->translateLabel()
                    ->disabledClick()
                    ->alignCenter(),
                Tables\Columns\IconColumn::make('is_premium')
                    ->translateLabel()
                    ->disabledClick()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('created_at')
                    ->translateLabel()
                    ->disabledClick()
                    ->date(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make(),
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
