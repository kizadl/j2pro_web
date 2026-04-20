<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\TvChannel;
use App\Models\TvSchedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TvScheduleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = TvSchedule::query()
            ->with('channel:id,name')
            ->where('is_active', true);

        if ($request->filled('channel_id')) {
            $query->where('tv_channel_id', (int) $request->string('channel_id'));
        }

        if ($request->filled('day_index')) {
            $query->where('day_index', (int) $request->string('day_index'));
        }

        $data = $query
            ->orderBy('day_index')
            ->orderBy('start_minutes')
            ->get()
            ->map(fn (TvSchedule $schedule) => $this->transform($schedule));

        return response()->json(['data' => $data]);
    }

    public function todayByChannel(TvChannel $tvChannel): JsonResponse
    {
        $dayIndex = now()->dayOfWeekIso;

        $data = TvSchedule::query()
            ->with('channel:id,name')
            ->where('tv_channel_id', $tvChannel->id)
            ->where('day_index', $dayIndex)
            ->where('is_active', true)
            ->orderBy('start_minutes')
            ->get()
            ->map(fn (TvSchedule $schedule) => $this->transform($schedule));

        return response()->json(['data' => $data]);
    }

    public function overview(): JsonResponse
    {
        $dayIndex = now()->dayOfWeekIso;

        $channels = TvChannel::query()->where('is_active', true)->orderBy('sort_order')->get();
        $schedules = TvSchedule::query()
            ->with('channel:id,name')
            ->where('is_active', true)
            ->where('day_index', $dayIndex)
            ->orderBy('start_minutes')
            ->get();

        return response()->json([
            'channels' => $channels->map(fn (TvChannel $channel) => [
                'id' => $channel->id,
                'name' => $channel->name,
                'slug' => $channel->slug,
                'category' => $channel->category,
                'description' => $channel->description,
                'image_url' => $this->storageUrl($channel->image_url),
                'source_type' => $channel->source_type,
                'stream_url' => $channel->stream_url,
                'youtube_id' => $channel->youtube_id,
                'watching_text' => $channel->watching_text,
                'is_active' => $channel->is_active,
            ]),
            'schedules' => $schedules->map(fn (TvSchedule $schedule) => $this->transform($schedule)),
        ]);
    }

    private function transform(TvSchedule $schedule): array
    {
        return [
            'id' => $schedule->id,
            'tv_channel_id' => $schedule->tv_channel_id,
            'channel_name' => $schedule->channel?->name,
            'title' => $schedule->title,
            'description' => $schedule->description,
            'image_url' => $this->storageUrl($schedule->image_url),
            'day_index' => $schedule->day_index,
            'day_name' => $schedule->day_name,
            'start_time' => substr((string) $schedule->start_time, 0, 5),
            'end_time' => substr((string) $schedule->end_time, 0, 5),
            'start_minutes' => $schedule->start_minutes,
            'end_minutes' => $schedule->end_minutes,
            'is_active' => $schedule->is_active,
        ];
    }

    private function storageUrl(?string $path): ?string
    {
        return $path ? asset('storage/' . ltrim($path, '/')) : null;
    }
}
