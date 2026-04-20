<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\HomeMenu;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    public function menus(): JsonResponse
    {
        $menus = HomeMenu::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(function (HomeMenu $menu) {
                return [
                    'id' => $menu->key,
                    'title' => $menu->title,
                    'subtitle' => $menu->subtitle ?? '',
                    'status' => $menu->status,
                ];
            });

        return response()->json([
            'data' => $menus,
        ]);
    }
}