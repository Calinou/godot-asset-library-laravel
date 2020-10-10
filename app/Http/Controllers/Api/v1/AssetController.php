<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Asset;
use App\AssetVersion;
use App\Http\Controllers\Controller;
use App\Http\Requests\ListAssets;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AssetController extends Controller
{
    /**
     * Return a paginated list of assets.
     */
    public function index(ListAssets $request): array
    {
        $validated = $request->validated();

        $itemsPerPage = $validated['max_results'] ?? Asset::ASSETS_PER_PAGE;
        $page = $validated['page'] ?? 1;

        $assets = Asset::with(['author', 'versions'])->filterSearch($validated)->map(function (Asset $asset) {
            // Flatten the author array for compatibility with the existing API
            // We have to unset the old value before setting it with the string
            // for some reason...
            $authorName = $asset['author']['username'];
            unset($asset['author']);
            $asset['author'] = $authorName;
            $asset['version'] = $asset['version_string'];

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
    public function show(Asset $asset): Asset
    {
        $asset->load(['author', 'previews']);

        // Flatten the author array for compatibility with the existing API
        // We have to unset the old value before setting it with the string
        // for some reason...
        $authorName = $asset['author']['username'];
        unset($asset['author']);
        $asset['author'] = $authorName;
        $asset['version'] = $asset['version_string'];

        return $asset;
    }

    /**
     * Increment asset's download_count and redirects to asset's download link.
     */
    public function download(Request $request, Asset $asset, AssetVersion $version = null): RedirectResponse
    {
        if ($version === null) {
            // if version is not specified, we try to check if we are not coming from the Godot editor by using the user
            // agent
            $userAgent = $request->server->get('HTTP_USER_AGENT');
            $from = substr($userAgent, 0, 11);
            if ($from === 'GodotEngine') {
                // if coming from the editor, we try to get the editor version and deliver the closest plugin version
                if (preg_match('/^GodotEngine\/(\d+\.\d+\.\d+).*$/', $userAgent, $m)) {
                    $engineVersion = $m[1];
                    $maxCommonLength = 0;
                    $maxCommonIndex = 0;
                    foreach ($asset->versions as $key => $v) {
                        $commonLen = $this->commonLength($engineVersion, $v->godot_version);
                        if ($commonLen > $maxCommonLength) {
                            $maxCommonIndex = $key;
                            $maxCommonLength = $commonLen;
                        }
                    }
                    if ($commonLen === 0) {
                        // if no matching version was found, we try to find a version of the plugin marked as "any"
                        foreach ($asset->versions as $key => $v) {
                            if ($version->godot_version === '*') {
                                $version = $v;
                                break;
                            }
                        }
                    } else {
                        $version = $asset->versions[$maxCommonIndex];
                    }
                }
            }
        }
        if ($version === null || $version->asset->asset_id !== $asset->asset_id) {
            $version = $asset->versions->last();
        }

        $asset->download_count += 1;
        $asset->save();

        return redirect($version->getDownloadUrlAttribute($asset->browse_url));
    }

    /**
     * scan the shortest string until you find a char that is not the same in the other string. Returns the obtained index.
     * @param $str
     * @param $ref
     * @return float
     */
    private function commonLength($str, $ref): float
    {
        $common = 0;
        for ($i = 0; $i < min(strlen($str), strlen($ref)); $i++) {
            if ($str[$i] === $ref[$i]) {
                $common++;
            } elseif ($ref[$i] === 'x') {
                $common += 0.5;
            } else {
                break;
            }
        }

        return $common;
    }
}
