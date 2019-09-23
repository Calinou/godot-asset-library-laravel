<?php

namespace App\Http\Controllers\Api\v1;

use App\Asset;
use App\Http\Requests\ListAssets;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;

class AssetController extends Controller
{
    public function index(ListAssets $request)
    {
        $validated = $request->validated();

        $itemsPerPage = $validated['max_results'] ?? Asset::ASSETS_PER_PAGE;
        $page = $validated['page'] ?? 1;

        $assets = Asset::filterSearch($validated);
        $paginator = new LengthAwarePaginator(
            $assets->slice(($page - 1) * $itemsPerPage, $itemsPerPage)->values(),
            $assets->count(),
            $itemsPerPage
        );

        return [
            'result' => $paginator->items(),
            'page' => $paginator->currentPage(),
            'pages' => $paginator->lastPage(),
            'page_length' => $paginator->perPage(),
            'total_items' => $paginator->total(),
        ];
    }

    public function show(int $id)
    {
        return ['name' => $id];
    }
}
