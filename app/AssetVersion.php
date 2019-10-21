<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AssetVersion extends Model
{
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
    public function asset()
    {
        return $this->belongsTo('App\Asset', 'asset_id');
    }

    /**
     * Return the download URL (will infer an URL if no custom URL is specified by the asset version).
     * For URL inference to work, the asset's browse URL must be passed manually.
     */
    public function getDownloadUrlAttribute(string $browseUrl = null): string
    {
        // Return the custom download URL if defined
        if ($this->getOriginal('download_url')) {
            return $this->getOriginal('download_url');
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
}
