<?php

declare(strict_types=1);

namespace Database\Seeds;

use App\Asset;
use App\AssetPreview;
use App\AssetVersion;
use App\User;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MigrateLegacyDbSeeder extends Seeder
{
    /**
     * Seed the application's database.
     * Old tables are removed as soon as they aren't relevant anymore.
     */
    public function run(): void
    {
        // Remove unused categories table
        DB::unprepared('DROP TABLE as_categories;');

        // Disable mass assignment protection on affected models
        User::unguard();
        Asset::unguard();

        $users = DB::table('as_users')->get();
        DB::unprepared('DROP TABLE as_users;');

        // Don't return users with a duplicate email address
        $newUsers = $users->unique('email', true)->map(function ($user) {
            return [
                'id' => $user->user_id,
                'username' => $user->username,
                'email' => $user->email,
                'password' => $user->password_hash,
                // Convert level-based privileges to a single administrator status
                'is_admin' => $user->type >= 50,
                'email_verified_at' => now(),
            ];
        });

        User::insert($newUsers->all());

        $assets = DB::table('as_assets')->get();
        DB::unprepared('DROP TABLE as_assets;');
        $newAssets = [];
        $newVersions = [];

        $assets->reject(function ($asset) {
            // Unpublished assets use a Godot version set to `0` in the old asset library
            return ! $asset->searchable || $asset->godot_version === 0;
        })->each(function ($asset) use (&$newAssets, &$newVersions) {
            $newAssets[] = [
                'asset_id' => $asset->asset_id,
                'title' => $asset->title,
                'description' => $asset->description,
                'html_description' => Markdown::convertToHtml($asset->description),
                // Category indices start at 0 in the new asset library,
                // whereas they started at 1 in the old one
                'category_id' => $asset->category_id - 1,
                'cost' => $asset->cost,
                'support_level_id' => $asset->support_level,
                'browse_url' => $asset->browse_url,
                'issues_url' => $asset->issues_url,
                'icon_url' => $asset->icon_url,
                'created_at' => $asset->modify_date,
                'modify_date' => $asset->modify_date,
                // Fix winston-yallow's user ID due to two accounts sharing the same email address
                'author_id' => $this->getAuthorId($asset->user_id),
            ];

            $newVersions[] = [
                'version_string' => $asset->version_string,
                'godot_version' => $this->getGodotVersion($asset->godot_version),
                'download_url' => $this->getDownloadUrl($asset->download_provider, $asset->browse_url, $asset->download_commit),
                'created_at' => $asset->modify_date,
                'modify_date' => $asset->modify_date,
                'asset_id' => $asset->asset_id,
            ];
        });

        Asset::insert($newAssets);
        AssetVersion::insert($newVersions);

        $assetPreviews = DB::table('as_asset_previews')->get();
        DB::unprepared('DROP TABLE as_asset_previews;');
        $newAssetPreviews = $assetPreviews->map(function ($assetPreview) {
            return [
                'type_id' => $assetPreview->type === 'video' ? AssetPreview::TYPE_VIDEO : AssetPreview::TYPE_IMAGE,
                'link' => $assetPreview->link,
                'thumbnail' => $assetPreview->thumbnail,
                'asset_id' => $assetPreview->asset_id,
            ];
        });

        AssetPreview::insert($newAssetPreviews->all());

        // Re-enable mass assignment protection on affected models
        User::reguard();
        Asset::reguard();
    }

    /**
     * Return the canonical author ID for an asset.
     * The old asset library allowed registering several accounts with the same
     * email address. The new one no longer allows this.
     */
    private function getAuthorId(int $userId): int
    {
        switch ($userId) {
            case 1418: // RafaelFreita
                return 1407;
            case 1951: // winston-yallow
                return 1703;
            default:
                return $userId;
        }
    }

    /**
     * Return the Godot version string suited for the new asset library.
     * Patch versions are merged together for simplicity's sake.
     */
    private function getGodotVersion(int $godotVersion): string
    {
        switch ($godotVersion) {
            case 20100:
            case 20103:
            case 20104:
                return '2.1.x';
            case 30000:
            case 30002:
            case 30004:
                return '3.0.x';
            case 30100:
                return '3.1.x';
            case 30200:
                return '3.2.x';
            default:
                throw new \Exception("Unknown Godot version: $godotVersion");
        }
    }

    /**
     * Return the download URL based on the asset's Git repository provider, browse URL and download commit.
     */
    private function getDownloadUrl(int $downloadProvider, string $browseUrl, string $downloadCommit): string
    {
        switch ($downloadProvider) {
            case -1: // Custom
                // The full download URL is stored in the download commit field
                return $downloadCommit;
            case 0: // GitHub
            case 1: // GitLab
            case 2: // BitBucket
            case 3: // NotABug
                return "$browseUrl/archive/$downloadCommit.zip";
            default:
                throw new \Exception("Unknown download provider: $downloadProvider");
        }
    }
}
