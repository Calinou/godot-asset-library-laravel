<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * An asset version. An asset may have multiple versions, each with their own
 * compatible Godot version.
 *
 * @property int $id The version's unique ID.
 * @property string $version_string The version's human-readable identifier (e.g. "0.4.2").
 * @property string $godot_version The Godot minor version the asset version is compatible with (e.g. "3.2").
 * @property string $download_url The download URL (will be inferred from the asset's `$browse_url` and the `$version_string` if empty).
 * @property \Illuminate\Support\Carbon $created_at The version's creation date.
 * @property \Illuminate\Support\Carbon $modify_date The version's last modification date.
 */
class AssetVersion extends Model
{
    /**
     * The available Godot versions for declaring the compatibility range.
     */
    public const GODOT_VERSIONS = [
        // Any version (should only be used for non-code assets)
        '*' => 'Any',

        // Any version in the Godot 3 series
        '3.x.x' => 'Godot 3.x.x',

        '3.0.x' => 'Godot 3.0.x',
        '3.1.x' => 'Godot 3.1.x',
        '3.2.x' => 'Godot 3.2.x',

        // Any version in the Godot 4 series
        '4.x.x' => 'Godot 4.x.x',

        '4.0.x' => 'Godot 4.0.x',
    ];

    /**
     * The available Godot versions that can be used for filtering in the API.
     * The Godot editor sends its version as `major.minor` in the `godot_version`
     * query string parameter, so this must be done for compatibility reasons.
     */
    public const GODOT_VERSION_FILTERS = [
        '3.0',
        '3.1',
        '3.2',
        '4.0',
    ];

    /**
     * The key used to store the last modification date and time.
     * This value has been changed from the default for consistency with Asset.
     */
    public const UPDATED_AT = 'modify_date';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'version_string',
        'godot_version',
        'download_url',
        'asset_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'asset_id',
        'modify_date',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'download_url',
    ];

    /**
     * Get the asset the version belongs to.
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo('App\Asset', 'asset_id');
    }

    /**
     * Return the download URL (will infer a URL if no custom URL is specified by the asset version).
     * For URL inference to work, the asset's browse URL must be passed manually.
     */
    public function getDownloadUrlAttribute(string $browseUrl = null): string
    {
        // Return the custom download URL if defined
        if ($this->getRawOriginal('download_url')) {
            return $this->getRawOriginal('download_url');
        }

        // A custom URL must be passed to infer the download URL
        if ($browseUrl === null) {
            return '';
        }

        $splitUrl = explode('/', $browseUrl);

        // Slug of the form `user/repository`
        $slug = "$splitUrl[3]/$splitUrl[4]";

        // Try to infer a download URL based on the repository host
        if ($splitUrl[2] === 'github.com') {
            return "https://github.com/$slug/archive/v$this->version_string.zip";
        } elseif ($splitUrl[2] === 'gitlab.com') {
            return "https://gitlab.com/$slug/-/archive/v$this->version_string.zip";
        }

        // Couldn't infer a download URL
        return '';
    }

    /**
     * Enforces HTTPS for the download URL.
     */
    public function setDownloadUrlAttribute(string $downloadUrl = null): void
    {
        // This field is nullable, so we check for the parameter first
        if ($downloadUrl) {
            $this->attributes['download_url'] = str_replace('http://', 'https://', $downloadUrl);
        }
    }
}
