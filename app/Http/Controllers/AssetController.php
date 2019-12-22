<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Asset;
use App\AssetPreview;
use App\AssetReview;
use App\AssetReviewReply;
use App\AssetVersion;
use App\Http\Requests\ListAssets;
use App\Http\Requests\SubmitAsset;
use App\Http\Requests\SubmitReview;
use App\Http\Requests\SubmitReviewReply;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        $input = $request->validated();

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

        $request->session()->flash('statusType', 'success');
        $request->session()->flash(
            'status',
            __('Your asset “:asset” has been submitted!', ['asset' => $asset->title])
        );

        $author = Auth::user();
        Log::info("$author submitted the asset $asset.");

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
        $input = $request->validated();

        // Remove submodel information from the input array as we don't want it here.
        // Instead, we update (or create) submodels a few lines below.
        $assetInput = $input;
        unset($assetInput['versions']);
        unset($assetInput['previews']);
        $asset->fill($assetInput);

        foreach ($input['versions'] as $version) {
            $version['asset_id'] = $asset->asset_id;
            // Prototypes don't have an ID associated, so we fall back to -1 (which will never match)
            AssetVersion::updateOrCreate(
                ['id' => $version['id'] ?? -1],
                $version
            );
        }

        foreach ($input['previews'] as $preview) {
            $preview['asset_id'] = $asset->asset_id;
            // Prototypes don't have an ID associated, so we fall back to -1 (which will never match)
            AssetPreview::updateOrCreate(
                ['preview_id' => $preview['id'] ?? -1],
                $preview
            );
        }

        $asset->save();

        $author = Auth::user();
        Log::info("$author updated the asset $asset.");

        return redirect(route('asset.show', $asset));
    }

    /**
     * Publishes an asset (only effective if it has been unpublished).
     * This can only be done by an administrator.
     * Once published, the asset will be visible in the list of assets again.
     */
    public function publish(Asset $asset, Request $request)
    {
        $asset->is_published = true;
        $asset->save();

        $request->session()->flash('statusType', 'success');
        $request->session()->flash(
            'status',
            __('The asset is now public again.')
        );

        $admin = Auth::user();
        Log::info("$admin unpublished $asset.");

        return redirect(route('asset.show', $asset));
    }

    /**
     * Unpublishes an asset. This can only be done by an administrator.
     * Once unpublished, the asset will no longer appear in the list of assets.
     */
    public function unpublish(Asset $asset, Request $request)
    {
        $asset->is_published = false;
        $asset->save();

        $request->session()->flash('statusType', 'success');
        $request->session()->flash(
            'status',
            __('The asset is no longer public.')
        );

        $admin = Auth::user();
        Log::info("$admin published $asset.");

        return redirect(route('asset.show', $asset));
    }

    /**
     * Mark an asset as archived. This can be done by its author or an administrator.
     * Once an asset is archived, it can no longer receive any reviews.
     * The asset can be unarchived at any time by its author or an administrator.
     */
    public function archive(Asset $asset, Request $request)
    {
        $asset->is_archived = true;
        $asset->save();

        $request->session()->flash('statusType', 'success');
        $request->session()->flash(
            'status',
            __('The asset is now marked as archived. Users can no longer leave reviews, but it can still be downloaded.')
        );

        $user = Auth::user();
        Log::info("$user archived $asset.");

        return redirect(route('asset.show', $asset));
    }

    /**
     * Mark an asset as unarchived.
     * This can be done by its author or an administrator.
     */
    public function unarchive(Asset $asset, Request $request)
    {
        $asset->is_archived = false;
        $asset->save();

        $request->session()->flash('statusType', 'success');
        $request->session()->flash(
            'status',
            __('The asset is no longer marked as archived. Welcome back!')
        );

        $user = Auth::user();
        Log::info("$user unarchived $asset.");

        return redirect(route('asset.show', $asset));
    }

    /**
     * Insert a newly created review into the database.
     */
    public function storeReview(Asset $asset, SubmitReview $request)
    {
        $review = new AssetReview();
        $review->fill($request->validated());
        $review->asset_id = $asset->asset_id;
        $review->author_id = Auth::user()->id ?? null;
        $review->save();

        $request->session()->flash('statusType', 'success');
        $request->session()->flash(
            'status',
            __('Your review for “:asset” has been posted!', ['asset' => $asset->title])
        );

        $user = Auth::user();
        $rating = $review->is_positive ? 'positive' : 'negative';
        $log = "$user submitted a $rating review for $asset";

        if ($review->comment) {
            $log .= ' with a comment';
        }

        Log::info("$log.");

        return redirect(route('asset.show', $asset));
    }

    /**
     * Update an existing review in the database.
     */
    public function updateReview(AssetReview $assetReview, SubmitReview $request)
    {
        $assetReview->fill($request->validated());
        $assetReview->save();

        $asset = $assetReview->asset;
        $user = Auth::user();

        if ($assetReview->author_id === $user->id) {
            $request->session()->flash('statusType', 'success');
            $request->session()->flash(
                'status',
                __('You edited your review for “:asset”!', ['asset' => $asset->title])
            );

            Log::info("$user edited their review for $asset.");
        } else {
            $request->session()->flash('statusType', 'success');
            $request->session()->flash(
                'status',
                __("You edited :author's review for “:asset”!", ['author' => $assetReview->author->name, 'asset' => $asset->title])
            );

            Log::info("$user edited $assetReview->author's review for $asset.");
        }

        return redirect(route('asset.show', $asset));
    }

    /**
     * Remove a review from the database.
     * This can only done by its author or an administrator.
     */
    public function destroyReview(AssetReview $assetReview, Request $request)
    {
        $asset = $assetReview->asset;
        $user = Auth::user();
        $author = $assetReview->author;

        $assetReview->delete();

        $request->session()->flash('statusType', 'success');

        if ($user && $user->is_admin && $assetReview->author->id !== $user->id) {
            $request->session()->flash(
                'status',
                __("You removed :author's review for “:asset”!", ['author' => $author->name, 'asset' => $asset->title])
            );

            Log::info("$user removed $author's review for $asset.");
        } else {
            $request->session()->flash(
                'status',
                __('You removed your review for “:asset”!', ['asset' => $asset->title])
            );

            Log::info("$user removed their review for $asset.");
        }

        return redirect(route('asset.show', $asset));
    }

    /**
     * Update a review with a reply from the asset author.
     */
    public function storeReviewReply(AssetReview $assetReview, SubmitReviewReply $request)
    {
        $reviewReply = new AssetReviewReply();
        $reviewReply->fill($request->validated());
        $reviewReply->asset_review_id = $assetReview->id;
        $reviewReply->save();

        $request->session()->flash('statusType', 'success');
        $request->session()->flash(
            'status',
            __("Your reply to :author's review has been posted!", ['author' => $assetReview->author->name])
        );

        $author = Auth::user();
        Log::info("$author replied to $assetReview->author's review for $assetReview->asset.");

        return redirect(route('asset.show', $assetReview->asset));
    }
}
