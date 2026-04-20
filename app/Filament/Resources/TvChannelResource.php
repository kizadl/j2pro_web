<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TvChannelResource\Pages;
use App\Models\TvChannel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class TvChannelResource extends Resource
{
    protected static ?string $model = TvChannel::class;
    protected static ?string $navigationIcon = 'heroicon-o-tv';
    protected static ?string $navigationGroup = 'TV Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')->required()->live(onBlur: true)
                ->afterStateUpdated(fn ($state, Forms\Set $set) => $set('slug', Str::slug((string) $state))),
            Forms\Components\TextInput::make('slug')->required()->unique(ignoreRecord: true),
            Forms\Components\TextInput::make('category'),
            Forms\Components\Textarea::make('description')->rows(3),
            Forms\Components\FileUpload::make('image_url')->disk('public')->directory('channels')->image(),
            Forms\Components\Select::make('source_type')
                ->options(['stream' => 'Stream', 'youtube' => 'YouTube'])
                ->required()
                ->default('stream')
                ->live(),
            Forms\Components\TextInput::make('stream_url')->url()->visible(fn (Forms\Get $get) => $get('source_type') === 'stream'),
            Forms\Components\TextInput::make('youtube_id')->visible(fn (Forms\Get $get) => $get('source_type') === 'youtube'),
            Forms\Components\TextInput::make('watching_text'),
            Forms\Components\TextInput::make('sort_order')->numeric()->default(0)->required(),
            Forms\Components\Toggle::make('is_active')->default(true),
            Forms\Components\Toggle::make('is_featured')->default(false),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\ImageColumn::make('image_url')->disk('public')->square(),
            Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('slug')->searchable(),
            Tables\Columns\TextColumn::make('source_type')->badge(),
            Tables\Columns\TextColumn::make('sort_order')->sortable(),
            Tables\Columns\IconColumn::make('is_active')->boolean(),
            Tables\Columns\IconColumn::make('is_featured')->boolean(),
            Tables\Columns\TextColumn::make('updated_at')->dateTime()->sortable(),
        ])->actions([
            Tables\Actions\EditAction::make(),
        ])->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTvChannels::route('/'),
            'create' => Pages\CreateTvChannel::route('/create'),
            'edit' => Pages\EditTvChannel::route('/{record}/edit'),
        ];
    }
}
