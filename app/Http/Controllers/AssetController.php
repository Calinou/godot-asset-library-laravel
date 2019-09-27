<?php

namespace App\Http\Controllers;

use App\Asset;
use App\Http\Requests\ListAssets;
use Illuminate\Pagination\LengthAwarePaginator;

class AssetController extends Controller
{
    public function index(ListAssets $request)
    {
        $validated = $request->validated();

        $itemsPerPage = $validated['max_results'] ?? Asset::ASSETS_PER_PAGE;
        $page = $validated['page'] ?? 1;

        $assets = Asset::with('author')->filterSearch($validated);
        $paginator = new LengthAwarePaginator(
            $assets->slice(($page - 1) * $itemsPerPage, $itemsPerPage)->values(),
            $assets->count(),
            $itemsPerPage
        );

        return view('index', ['assets' => $paginator->items()]);
    }

    /**
     * TODO: Implement single asset display.
     */
    public function show(int $id)
    {
        return view('asset', ['id' => $id]);
    }
}
