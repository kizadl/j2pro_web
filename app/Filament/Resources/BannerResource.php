<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationLabel = 'Banners';
    protected static ?string $navigationGroup = 'Home Management';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Banner Content')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('subtitle')
                        ->maxLength(255),

                    Forms\Components\FileUpload::make('image_url')
                        ->label('Banner Image')
                        ->image()
                        ->disk('public')
                        ->directory('banners')
                        ->visibility('public')
                        ->imageEditor()
                        ->imagePreviewHeight('180')
                        ->panelAspectRatio('16:8')
                        ->panelLayout('integrated')
                        ->downloadable()
                        ->openable()
                        ->required(),

                    Forms\Components\Toggle::make('is_live')
                        ->label('Show LIVE Badge')
                        ->default(false),

                    Forms\Components\Toggle::make('is_active')
                        ->default(true),

                    Forms\Components\TextInput::make('sort_order')
                        ->numeric()
                        ->default(0)
                        ->required(),
                ])
                ->columns(2),

            Forms\Components\Section::make('Link Action')
                ->schema([
                    Forms\Components\Select::make('link_type')
                        ->options([
                            'none' => 'None',
                            'internal' => 'Internal App Page',
                            'external' => 'External URL',
                        ])
                        ->default('none')
                        ->required()
                        ->live(),

                    Forms\Components\TextInput::make('link_value')
                        ->label('Link Value')
                        ->placeholder('tv | radio | news | https://example.com')
                        ->visible(fn (Forms\Get $get) => $get('link_type') !== 'none')
                        ->helperText('Internal: tv, radio, news. External: full URL.')
                        ->maxLength(1000),
                ])
                ->columns(2),

            Forms\Components\Section::make('Schedule')
                ->schema([
                    Forms\Components\DateTimePicker::make('start_at'),
                    Forms\Components\DateTimePicker::make('end_at'),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->columns([
                Tables\Columns\ImageColumn::make('image_url')
                    ->disk('public')
                    ->square(false)
                    ->height(56),

                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('subtitle')
                    ->limit(30),

                Tables\Columns\TextColumn::make('link_type')
                    ->badge(),

                Tables\Columns\IconColumn::make('is_live')
                    ->boolean(),

                Tables\Columns\IconColumn::make('is_active')
                    ->boolean(),

                Tables\Columns\TextColumn::make('sort_order')
                    ->sortable(),

                Tables\Columns\TextColumn::make('start_at')
                    ->dateTime('d M Y H:i'),

                Tables\Columns\TextColumn::make('end_at')
                    ->dateTime('d M Y H:i'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}