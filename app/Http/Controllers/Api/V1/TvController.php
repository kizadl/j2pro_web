<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\TvChannel;
use App\Models\TvSchedule;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TvController extends Controller
{
    public function channels(): JsonResponse
    {
        $channels = TvChannel::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn (TvChannel $channel) => $this->transformChannel($channel));

        return response()->json([
            'data' => $channels,
        ]);
    }

    public function schedules(Request $request): JsonResponse
    {
        $query = TvSchedule::query()
            ->with('channel')
            ->where('is_active', true);

        if ($request->filled('channel_id')) {
            $query->where('tv_channel_id', $request->integer('channel_id'));
        }

        if ($request->filled('day_index')) {
            $query->where('day_index', $request->integer('day_index'));
        }

        $schedules = $query
            ->orderBy('day_index')
            ->orderBy('start_minutes')
            ->get()
            ->map(fn (TvSchedule $schedule) => $this->transformSchedule($schedule));

        return response()->json([
            'data' => $schedules,
        ]);
    }

    public function overview(): JsonResponse
    {
        $channels = TvChannel::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $schedules = TvSchedule::query()
            ->with('channel')
            ->where('is_active', true)
            ->orderBy('day_index')
            ->orderBy('start_minutes')
            ->get();

        return response()->json([
            'channels' => $channels->map(fn (TvChannel $channel) => $this->transformChannel($channel)),
            'schedules' => $schedules->map(fn (TvSchedule $schedule) => $this->transformSchedule($schedule)),
        ]);
    }

    public function showChannel(int $id): JsonResponse
    {
        $channel = TvChannel::query()
            ->where('is_active', true)
            ->findOrFail($id);

        return response()->json([
            'data' => $this->transformChannel($channel),
        ]);
    }

    public function todaySchedules(int $id): JsonResponse
    {
        $channel = TvChannel::query()
            ->where('is_active', true)
            ->findOrFail($id);

        $now = now();
        $dayIndex = (int) $now->dayOfWeekIso;
        $nowMinutes = ($now->hour * 60) + $now->minute;

        $schedules = TvSchedule::query()
            ->where('tv_channel_id', $channel->id)
            ->where('is_active', true)
            ->where('day_index', $dayIndex)
            ->orderBy('start_minutes')
            ->get()
            ->map(function (TvSchedule $schedule) use ($nowMinutes) {
                $data = $this->transformSchedule($schedule);
                $data['is_live_now'] = $this->isLiveNow(
                    $schedule->start_minutes,
                    $schedule->end_minutes,
                    $nowMinutes
                );

                return $data;
            });

        return response()->json([
            'channel' => $this->transformChannel($channel),
            'data' => $schedules,
        ]);
    }

    private function transformChannel(TvChannel $channel): array
    {
        $now = now();
        $dayIndex = (int) $now->dayOfWeekIso;
        $nowMinutes = ($now->hour * 60) + $now->minute;

        $isLive = TvSchedule::query()
            ->where('tv_channel_id', $channel->id)
            ->where('is_active', true)
            ->where('day_index', $dayIndex)
            ->get()
            ->contains(function (TvSchedule $schedule) use ($nowMinutes) {
                return $this->isLiveNow(
                    $schedule->start_minutes,
                    $schedule->end_minutes,
                    $nowMinutes
                );
            });

        return [
            'id' => $channel->id,
            'name' => $channel->name,
            'slug' => $channel->slug,
            'category' => $channel->category ?? '',
            'description' => $channel->description ?? '',
            'image_url' => $this->fullImageUrl($channel->image_url),
            'source_type' => $channel->source_type,
            'stream_url' => $channel->stream_url,
            'youtube_id' => $channel->youtube_id,
            'watching_text' => $channel->watching_text ?? '',
            'is_active' => (bool) $channel->is_active,
            'is_live' => $isLive,
        ];
    }

    private function transformSchedule(TvSchedule $schedule): array
    {
        return [
            'id' => $schedule->id,
            'tv_channel_id' => $schedule->tv_channel_id,
            'channel_name' => $schedule->channel?->name ?? '',
            'title' => $schedule->title,
            'description' => $schedule->description ?? '',
            'image_url' => $this->fullImageUrl($schedule->image_url),
            'day_name' => $schedule->day_name,
            'day_index' => $schedule->day_index,
            'start_time' => $this->formatTime($schedule->start_time),
            'end_time' => $this->formatTime($schedule->end_time),
            'is_active' => (bool) $schedule->is_active,
        ];
    }

    private function formatTime($value): string
    {
        if (blank($value)) {
            return '';
        }

        try {
            return Carbon::parse($value)->format('H:i');
        } catch (\Throwable $e) {
            return (string) $value;
        }
    }

    private function fullImageUrl(?string $path): string
    {
        if (blank($path)) {
            return '';
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return asset('storage/' . ltrim($path, '/'));
    }

    private function isLiveNow(int $startMinutes, int $endMinutes, int $nowMinutes): bool
    {
        if ($startMinutes <= $endMinutes) {
            return $nowMinutes >= $startMinutes && $nowMinutes < $endMinutes;
        }

        return $nowMinutes >= $startMinutes || $nowMinutes < $endMinutes;
    }
}