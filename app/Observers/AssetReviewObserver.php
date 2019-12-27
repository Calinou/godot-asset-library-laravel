<?php

namespace App\Observers;

use App\AssetReview;

class AssetReviewObserver
{
    /**
     * Read all reviews to calculate the score.
     * It's slower with large amounts of reviews, but it works well
     * when the review was edited (it avoids duplicating scores).
     */
    private function calculateScore(AssetReview $assetReview): void
    {
        if ($assetReview->asset) {
            $score = 0;
            foreach ($assetReview->asset->reviews as $review) {
                $score += $review->is_positive ? 1 : -1;
            }

            $assetReview->asset->score = $score;
            $assetReview->asset->save();
        }
    }

    /**
     * Handle the asset review "saved" event.
     * This calculates the asset score and stores it for performance
     * (so we don't have to fetch all reviews on every page load).
     */
    public function saved(AssetReview $assetReview): void
    {
        $this->calculateScore($assetReview);
    }

    public function deleted(AssetReview $assetReview): void
    {
        $this->calculateScore($assetReview);
    }
}
