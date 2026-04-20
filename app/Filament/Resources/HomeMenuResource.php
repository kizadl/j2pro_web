<?php

namespace App\Filament\Resources;

use App\Filament\Resources\HomeMenuResource\Pages;
use App\Models\HomeMenu;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;

class HomeMenuResource extends Resource
{
    protected static ?string $model = HomeMenu::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'Home Menus';
    protected static ?string $navigationGroup = 'Home Management';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('key')
                ->required()
                ->maxLength(50)
                ->disabled()
                ->dehydrated(false)
                ->helperText('Key menu dikunci: tv, radio, media'),

            Forms\Components\TextInput::make('title')
                ->required(),

            Forms\Components\TextInput::make('subtitle'),

            Forms\Components\Select::make('status')
                ->options([
                    'on_air' => 'On Air',
                    'updated' => 'Updated',
                    'offline' => 'Offline',
                ])
                ->required(),

            Forms\Components\Toggle::make('is_active')
                ->default(true),

            Forms\Components\TextInput::make('sort_order')
                ->numeric()
                ->default(0),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('key')->searchable(),
                Tables\Columns\TextColumn::make('title')->searchable(),
                Tables\Columns\TextColumn::make('status')->badge(),
                Tables\Columns\IconColumn::make('is_active')->boolean(),
                Tables\Columns\TextColumn::make('sort_order')->sortable(),
            ])
            ->defaultSort('sort_order')
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListHomeMenus::route('/'),
            'edit' => Pages\EditHomeMenu::route('/{record}/edit'),
        ];
    }
}