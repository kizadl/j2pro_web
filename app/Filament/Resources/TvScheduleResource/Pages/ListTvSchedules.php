<?php
namespace App\Filament\Resources\TvScheduleResource\Pages;
use App\Filament\Resources\TvScheduleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
class ListTvSchedules extends ListRecords { protected static string $resource = TvScheduleResource::class; protected function getHeaderActions(): array { return [Actions\CreateAction::make()]; } }
