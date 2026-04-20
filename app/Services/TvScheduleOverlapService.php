<?php

namespace App\Services;

use App\Models\TvSchedule;

class TvScheduleOverlapService
{
    public function hasOverlap(array $data, mixed $ignoreId = null): bool
    {
        $query = TvSchedule::query()
            ->where('tv_channel_id', $data['tv_channel_id'])
            ->where('day_index', $data['day_index']);

        if ($ignoreId) {
            $query->whereKeyNot($ignoreId);
        }

        return $query->get()->contains(function (TvSchedule $schedule) use ($data): bool {
            return $data['start_minutes'] < $schedule->end_minutes
                && $data['end_minutes'] > $schedule->start_minutes;
        });
    }
}
