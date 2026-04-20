<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TvScheduleResource\Pages;
use App\Models\TvChannel;
use App\Models\TvSchedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Validation\ValidationException;

class TvScheduleResource extends Resource
{
    protected static ?string $model = TvSchedule::class;
    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';
    protected static ?string $navigationGroup = 'TV Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('tv_channel_id')
                ->label('TV Channel')
                ->options(TvChannel::query()->orderBy('name')->pluck('name', 'id'))
                ->required()
                ->searchable(),
            Forms\Components\TextInput::make('title')->required(),
            Forms\Components\Textarea::make('description')->rows(3),
            Forms\Components\FileUpload::make('image_url')->disk('public')->directory('schedules')->image(),
            Forms\Components\Select::make('day_index')
                ->options([1=>'Monday',2=>'Tuesday',3=>'Wednesday',4=>'Thursday',5=>'Friday',6=>'Saturday',7=>'Sunday'])
                ->required()
                ->live()
                ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('day_name', self::dayName((int) $state))),
            Forms\Components\Hidden::make('day_name'),
            Forms\Components\TimePicker::make('start_time')->seconds(false)->required()->live(),
            Forms\Components\TimePicker::make('end_time')->seconds(false)->required()->live(),
            Forms\Components\Toggle::make('is_active')->default(true),
        ])->columns(2);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        return self::normalizeAndValidate($data);
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        return self::normalizeAndValidate($data, request()->route('record'));
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('channel.name')->label('Channel')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('title')->searchable(),
            Tables\Columns\TextColumn::make('day_name')->badge(),
            Tables\Columns\TextColumn::make('start_time'),
            Tables\Columns\TextColumn::make('end_time'),
            Tables\Columns\IconColumn::make('is_active')->boolean(),
            Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
        ])->filters([
            Tables\Filters\SelectFilter::make('tv_channel_id')->label('Channel')->options(TvChannel::query()->pluck('name', 'id')),
            Tables\Filters\SelectFilter::make('day_index')->options([1=>'Monday',2=>'Tuesday',3=>'Wednesday',4=>'Thursday',5=>'Friday',6=>'Saturday',7=>'Sunday']),
        ])->actions([
            Tables\Actions\EditAction::make(),
        ])->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTvSchedules::route('/'),
            'create' => Pages\CreateTvSchedule::route('/create'),
            'edit' => Pages\EditTvSchedule::route('/{record}/edit'),
        ];
    }

    private static function dayName(int $index): string
    {
        return [1=>'Monday',2=>'Tuesday',3=>'Wednesday',4=>'Thursday',5=>'Friday',6=>'Saturday',7=>'Sunday'][$index] ?? 'Monday';
    }

    private static function toMinutes(string $time): int
    {
        [$h, $m] = array_pad(explode(':', $time), 2, '0');
        return ((int) $h * 60) + (int) $m;
    }

    private static function normalizeAndValidate(array $data, mixed $record = null): array
    {
        $data['day_name'] = self::dayName((int) $data['day_index']);
        $data['start_minutes'] = self::toMinutes($data['start_time']);
        $data['end_minutes'] = self::toMinutes($data['end_time']);

        if ($data['start_minutes'] >= $data['end_minutes']) {
            throw ValidationException::withMessages(['end_time' => 'End time must be after start time.']);
        }

        $query = TvSchedule::query()
            ->where('tv_channel_id', $data['tv_channel_id'])
            ->where('day_index', $data['day_index']);

        if ($record) {
            $query->whereKeyNot($record);
        }

        $overlap = $query->get()->first(function (TvSchedule $schedule) use ($data): bool {
            return $data['start_minutes'] < $schedule->end_minutes && $data['end_minutes'] > $schedule->start_minutes;
        });

        if ($overlap) {
            throw ValidationException::withMessages(['start_time' => 'Schedule overlaps with an existing program on this channel and day.']);
        }

        return $data;
    }
}
