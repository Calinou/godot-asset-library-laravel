<?php

namespace App\Http\Controllers;

use App\Asset;
use App\AssetReview;
use App\Http\Requests\ListAssets;
use App\Http\Requests\SubmitAsset;
use App\Http\Requests\SubmitReview;
use Illuminate\Support\Facades\Auth;
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

        $assets = Asset::with('author', 'versions')->filterSearch($validated);
        $paginator = new LengthAwarePaginator(
            $assets->slice(intval(($page - 1) * $itemsPerPage), $itemsPerPage)->values(),
            $assets->count(),
            $itemsPerPage
        );

        return view('asset.index', ['assets' => $paginator]);
    }

    /**
     * Display a single asset.
     */
    public function show(Asset $asset)
    {
        // Eager load review authors to avoid N+1 queries
        $asset->load('reviews.author');

        return view('asset.show', ['asset' => $asset]);
    }

    /**
     * Display the form used to submit an asset.
     */
    public function create()
    {
        return view('asset.create', ['editing' => false]);
    }

    /**
     * Insert a newly created asset into the database.
     */
    public function store(SubmitAsset $request)
    {
        $input = $request->all();

        // Remove submodel information from the input array as we don't want it here
        $assetInput = $input;
        unset($assetInput['versions']);
        unset($assetInput['previews']);
        $asset = new Asset();
        $asset->fill($assetInput);
        // The user must be authenticated to submit an asset.
        // The null coalesce is just here to please PHPStan :)
        $asset->author_id = Auth::user()->id ?? null;

        // Save the asset without its submodels, so that submodels can be saved.
        // This must be done *before* creating submodels, otherwise the asset ID
        // can't be fetched by Eloquent.
        $asset->save();

        // Create and save the version and preview submodels

        if (array_key_exists('versions', $input)) {
            $asset->versions()->createMany($input['versions']);
        }

        if (array_key_exists('previews', $input)) {
            $asset->previews()->createMany($input['previews']);
        }

        // Save the asset with its submodels
        $asset->save();

        return redirect(route('asset.show', $asset));
    }

    /**
     * Display the form used to edit an asset.
     */
    public function edit(Asset $asset)
    {
        return view('asset.create', [
            'editing' => true,
            'asset' => $asset,
        ]);
    }

    /**
     * Store modifications to an existing asset.
     */
    public function update(Asset $asset, SubmitAsset $request)
    {
        $input = $request->all();

        // Remove submodel information from the input array as we don't want it here
        $assetInput = $input;
        unset($assetInput['versions']);
        unset($assetInput['previews']);
        $asset->fill($assetInput);

        // Recreate the version and preview submodels

        $versions = $asset->versions()->get();
        foreach ($versions as $version) {
            $version->delete();
        }

        $previews = $asset->previews()->get();
        foreach ($previews as $preview) {
            $preview->delete();
        }

        if (array_key_exists('versions', $input)) {
            $asset->versions()->createMany($input['versions']);
        }

        if (array_key_exists('previews', $input)) {
            $asset->previews()->createMany($input['previews']);
        }

        $asset->save();

        return redirect(route('asset.show', $asset));
    }

    /**
     * Insert a newly created review into the database.
     */
    public function storeReview(Asset $asset, SubmitReview $request)
    {
        $review = new AssetReview();
        $review->fill($request->all());
        $review->asset_id = $asset->asset_id;
        $review->author_id = Auth::user()->id ?? null;
        $review->save();

        return redirect(route('asset.show', $asset));
    }
}
