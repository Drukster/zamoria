<?php

namespace App\Filament\Resources;

use App\Enums\SupportRequestStatusEnum;
use App\Enums\SupportRequestTypeEnum;
use App\Filament\Resources\SupportRequestResource\Pages;
use App\Models\SupportRequest;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Log;
use SergiX44\Nutgram\Nutgram;

class SupportRequestResource extends Resource
{
    protected static ?string $model = SupportRequest::class;
    protected static ?string $pluralLabel = 'Обращения';
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';
    protected static ?string $navigationGroup = 'Поддержка';
    protected static ?string $label = 'обращение';
    protected static ?string $recordTitleAttribute = 'message';

    public static function infolist(
        Infolist $infolist
    ): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Основная информация')
                    ->schema([
                        TextEntry::make('telegram_user_id')
                            ->translateLabel()
                            ->badge(),
                        TextEntry::make('message')
                            ->translateLabel()
                            ->helperText('Содержание обращения')
                            ->columnSpan(2),
                        Grid::make()
                            ->columns(12)
                            ->schema([
                                TextEntry::make('type')
                                    ->columnSpan(6)
                                    ->formatStateUsing(function ($state) {
                                        return SupportRequestTypeEnum::valuesWithLabels()[$state];
                                    }),
                                TextEntry::make('status')
                                    ->columnSpan(6)
                                    ->formatStateUsing(function ($state) {
                                        return SupportRequestStatusEnum::valuesWithLabels()[$state];
                                    })
                            ])

                    ])
            ]);
    }

    public static function table(
        Table $table
    ): Table
    {
        return $table
            ->groups([
                Tables\Grouping\Group::make('type')
                    ->label('Тип обращения')
                    ->getTitleFromRecordUsing(fn(SupportRequest $record): string => SupportRequestTypeEnum::from($record->type)->label())
                    ->getDescriptionFromRecordUsing(fn(SupportRequest $record): string => 'Количество: ' . $record->query()->where('type', $record->type)->count()),
                Tables\Grouping\Group::make('status')
                    ->label('Статус')
                    ->getTitleFromRecordUsing(fn(SupportRequest $record): string => SupportRequestStatusEnum::from($record->status)->label())
                    ->getDescriptionFromRecordUsing(fn(SupportRequest $record): string => 'Количетсво: ' . $record->query()->where('status', $record->status)->count()),
            ])
            ->defaultGroup('type')
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->translateLabel()
                    ->disabledClick()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('message')
                    ->translateLabel()
                    ->disabledClick()
                    ->limit(100)
                    ->tooltip(fn(SupportRequest $record): string => $record->message),
                Tables\Columns\TextColumn::make('type')
                    ->translateLabel()
                    ->disabledClick()
                    ->formatStateUsing(fn($state) => SupportRequestTypeEnum::valuesWithLabels()[$state])
                    ->color(fn($state) => match ($state) {
                        SupportRequestTypeEnum::COMPLAINT->value => 'danger',
                        SupportRequestTypeEnum::SUGGESTION->value => 'success',
                        SupportRequestTypeEnum::QUESTION->value => 'info',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('status')
                    ->translateLabel()
                    ->disabledClick()
                    ->badge()
                    ->formatStateUsing(function ($state) {
                        return SupportRequestStatusEnum::valuesWithLabels()[$state];
                    }),
                Tables\Columns\TextColumn::make('telegram_user_id')
                    ->translateLabel()
                    ->badge()
                    ->disabledClick()
                    ->alignCenter()
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    self::makeSendResponseAction(),
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\DeleteAction::make()
                ])
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSupportRequests::route('/'),
            'create' => Pages\CreateSupportRequest::route('/create'),
        ];
    }

    public static function makeSendResponseAction()
    {
        return Tables\Actions\Action::make('respond')
            ->translateLabel()
            ->modalHeading('Ответ пользователю')
            ->icon('heroicon-s-arrow-uturn-left')
            ->color('success')
            ->form([
                Textarea::make('response')
                    ->translateLabel()
                    ->required()
                    ->minLength(10)
                    ->maxLength(3000)
                    ->columnSpanFull()
                    ->rows(6)
            ])
            ->visible(fn(SupportRequest $record) => $record->status == SupportRequestStatusEnum::CLOSED)
            ->action(
                function (
                    SupportRequest $record,
                    array          $data
                ) {
                    try {
                        app(Nutgram::class)
                            ->sendMessage(
                                text: "<b>📬 Ответ на ваше обращение #{$record->id}</b>\n\n"
                                . "<i>Ваше обращение:</i>\n"
                                . "<blockquote>" . htmlspecialchars($record->message) . "</blockquote>\n\n"
                                . "<i>Наш ответ:</i>\n"
                                . "<blockquote>" . htmlspecialchars($data['response']) . "</blockquote>",
                                chat_id: $record->telegram_user_id,
                                parse_mode: 'HTML'
                            );

                        $record->status = SupportRequestStatusEnum::CLOSED;
                        $record->response = $data['response'];
                        $record->save();

                        Notification::make()
                            ->title('Ответ отправлен')
                            ->success()
                            ->send();

                    } catch (\Exception $ex) {
                        Log::error(
                            message: $ex->getMessage(),
                            context: [
                                'file' => $ex->getFile(),
                                'line' => $ex->getLine(),
                                'trace' => $ex->getTraceAsString(),
                            ]
                        );

                        Notification::make()
                            ->title('Ошибка!')
                            ->body('Произошла ошибка при попытке ответить на обращение пользователя.')
                            ->danger()
                            ->send();

                        throw $ex;
                    }
                }
            );
    }

    public static function canCreate(): bool
    {
        return false;
    }
}
