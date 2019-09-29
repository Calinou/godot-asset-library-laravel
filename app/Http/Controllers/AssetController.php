<?php

namespace App\Http\Controllers;

use App\Asset;
use App\Http\Requests\ListAssets;
use Illuminate\Pagination\LengthAwarePaginator;

class AssetController extends Controller
{
    /**
     * Display a paginated list of assets.
     */
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
     * Display a single asset.
     */
    public function show(Asset $asset)
    {
        return view('asset', ['asset' => $asset]);
    }
}
