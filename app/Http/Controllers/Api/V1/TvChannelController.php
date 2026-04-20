<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\TvChannel;
use Illuminate\Http\JsonResponse;

class TvChannelController extends Controller
{
    public function index(): JsonResponse
    {
        $channels = TvChannel::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get()
            ->map(fn (TvChannel $channel) => $this->transform($channel));

        return response()->json(['data' => $channels]);
    }

    public function show(TvChannel $tvChannel): JsonResponse
    {
        return response()->json(['data' => $this->transform($tvChannel)]);
    }

    private function transform(TvChannel $channel): array
    {
        return [
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
            'is_featured' => $channel->is_featured,
            'sort_order' => $channel->sort_order,
        ];
    }

    private function storageUrl(?string $path): ?string
    {
        return $path ? asset('storage/' . ltrim($path, '/')) : null;
    }
}
