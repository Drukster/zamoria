<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DuaResource\Pages;
use App\Models\Dua;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DuaResource extends Resource
{
    protected static ?string $model = Dua::class;
    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $pluralLabel = 'Дуа';
    protected static ?string $label = 'дуа';
    protected static ?string $navigationGroup = 'Духовные практики';

    public static function form(
        Form $form
    ): Form
    {
        return $form
            ->schema([
                Grid::make()
                    ->columns(12)
                    ->schema([
                        Section::make('Основная информация')
                            ->columnSpan(8)
                            ->schema([
                                TextInput::make('title')
                                    ->translateLabel()
                                    ->string()
                                    ->required()
                                    ->maxLength(100),
                                Textarea::make('content')
                                    ->translateLabel()
                                    ->maxLength(10000)
                                    ->nullable()
                                    ->rows(8)
                                    ->autosize()
                                    ->cols(20),
                                Textarea::make('arabic_text')
                                    ->translateLabel()
                                    ->maxLength(10000)
                                    ->nullable()
                                    ->rows(8)
                                    ->autosize()
                                    ->cols(20),
                                Textarea::make('translation')
                                    ->translateLabel()
                                    ->maxLength(10000)
                                    ->nullable()
                                    ->rows(8)
                                    ->autosize()
                                    ->cols(20),
                                Textarea::make('transliteration')
                                    ->translateLabel()
                                    ->maxLength(10000)
                                    ->nullable()
                                    ->rows(8)
                                    ->autosize()
                                    ->cols(20),
                            ]),
                        Section::make('Дополнительная информация')
                            ->columnSpan(4)
                            ->schema([
                                Select::make('category_id')
                                    ->translateLabel()
                                    ->relationship('category', 'title')
                                    ->preload()
                                    ->searchable()
                            ])
                    ]),
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
                Tables\Columns\TextColumn::make('category.title')
                    ->translateLabel()
                    ->disabledClick(),
                Tables\Columns\TextColumn::make('title')
                    ->translateLabel()
                    ->disabledClick(),
                Tables\Columns\TextColumn::make('content')
                    ->translateLabel()
                    ->tooltip(fn(Dua $record): string => $record->content)
                    ->limit()
                    ->disabledClick(),
                Tables\Columns\TextColumn::make('arabic_text')
                    ->translateLabel()
                    ->tooltip(fn(Dua $record): string => $record->arabic_text)
                    ->limit()
                    ->disabledClick(),
                Tables\Columns\TextColumn::make('translation')
                    ->translateLabel()
                    ->disabledClick()
                    ->tooltip(fn(Dua $record): string => $record->translation)
                    ->limit(),
                Tables\Columns\TextColumn::make('transliteration')
                    ->translateLabel()
                    ->disabledClick()
                    ->tooltip(fn(Dua $record): string => $record->transliteration)
                    ->limit()

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
            'index' => Pages\ListDuas::route('/'),
            'create' => Pages\CreateDua::route('/create'),
            'edit' => Pages\EditDua::route('/{record}/edit'),
        ];
    }
}
