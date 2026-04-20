<?php

namespace App\Filament\Resources\HomeMenuResource\Pages;

use App\Filament\Resources\HomeMenuResource;
use Filament\Resources\Pages\ListRecords;

class ListHomeMenus extends ListRecords
{
    protected static string $resource = HomeMenuResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}