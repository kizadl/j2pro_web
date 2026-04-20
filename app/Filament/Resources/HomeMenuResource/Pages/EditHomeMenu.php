<?php

namespace App\Filament\Resources\HomeMenuResource\Pages;

use App\Filament\Resources\HomeMenuResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHomeMenu extends EditRecord
{
    protected static string $resource = HomeMenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
