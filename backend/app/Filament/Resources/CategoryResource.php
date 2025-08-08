<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Духовные практики';
    protected static ?string $navigationBadgeTooltip = 'Количество новых дуа';
    protected static ?string $pluralLabel = 'Категории';
    protected static ?string $label = ' категорию';

    public static function form(
        Form $form
    ): Form
    {
        return $form
            ->schema([
                Section::make('Основная информация')
                    ->schema([
                        TextInput::make('title')
                            ->translateLabel()
                            ->required()
                            ->maxLength(100)
                            ->string(),
                        TextInput::make('slug')
                            ->translateLabel()
                            ->required()
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
                    ->alignCenter()
                    ->disabledClick(),
                TextColumn::make('title')
                    ->translateLabel()
                    ->disabledClick(),
                Tables\Columns\TextColumn::make('slug')
                    ->translateLabel()
                    ->disabledClick(),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make()
                ])
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
