<?php
namespace App\Filament\Resources\TvScheduleResource\Pages;
use App\Filament\Resources\TvScheduleResource;
use Filament\Resources\Pages\EditRecord;
class EditTvSchedule extends EditRecord { protected static string $resource = TvScheduleResource::class; protected function mutateFormDataBeforeSave(array $data): array { return TvScheduleResource::mutateFormDataBeforeSave($data); } }
