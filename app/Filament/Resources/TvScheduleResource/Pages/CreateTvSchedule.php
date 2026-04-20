<?php
namespace App\Filament\Resources\TvScheduleResource\Pages;
use App\Filament\Resources\TvScheduleResource;
use Filament\Resources\Pages\CreateRecord;
class CreateTvSchedule extends CreateRecord { protected static string $resource = TvScheduleResource::class; protected function mutateFormDataBeforeCreate(array $data): array { return TvScheduleResource::mutateFormDataBeforeCreate($data); } }
