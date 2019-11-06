<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Asset;
use App\Http\Requests\ListAssets;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator;

class AssetController extends Controller
{
    /**
     * Return a paginated list of assets.
     */
    public function index(ListAssets $request)
    {
        $validated = $request->validated();

        $itemsPerPage = $validated['max_results'] ?? Asset::ASSETS_PER_PAGE;
        $page = $validated['page'] ?? 1;

        $assets = Asset::with(['author', 'versions'])->filterSearch($validated)->map(function (Asset $asset) {
            // Flatten the author array for compatibility with the existing API
            // We have to unset the old value before setting it with the string
            // for some reason...
            $authorName = $asset['author']['name'];
            unset($asset['author']);
            $asset['author'] = $authorName;

            return $asset;
        });
        $paginator = new LengthAwarePaginator(
            $assets->slice(intval(($page - 1) * $itemsPerPage), $itemsPerPage)->values(),
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

    /**
     * Return information about a single asset.
     */
    public function show(Asset $asset)
    {
        $asset->load(['author', 'previews']);

        // Flatten the author array for compatibility with the existing API
        // We have to unset the old value before setting it with the string
        // for some reason...
        $authorName = $asset['author']['name'];
        unset($asset['author']);
        $asset['author'] = $authorName;

        return $asset;
    }
}
