<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Asset extends Model
{
    /**
     * The key used to store the last modification date and time.
     * This value has been changed from the default for compatibility with the
     * existing asset library API.
     */
    public const UPDATED_AT = 'modify_date';

    /**
     * The number of assets per page to display by default.
     */
    public const ASSETS_PER_PAGE = 10;

    /**
     * The support level voluntarily set by users who are submitting assets
     * in "testing" version (can be unstable).
     * Will be distinguished on the Web interface.
     */
    public const SUPPORT_LEVEL_TESTING = 0;

    /**
     * The default support level for newly submitted assets.
     */
    public const SUPPORT_LEVEL_COMMUNITY = 1;

    /**
     * The support level for assets submitted on behalf of the Godot project.
     * Will be distinguished on the Web interface.
     */
    public const SUPPORT_LEVEL_OFFICIAL = 2;

    public const SUPPORT_LEVEL_MAX = 3;

    public const CATEGORY_2D_TOOLS = 0;
    public const CATEGORY_3D_TOOLS = 1;
    public const CATEGORY_SHADERS = 2;
    public const CATEGORY_MATERIALS = 3;
    public const CATEGORY_TOOLS = 4;
    public const CATEGORY_SCRIPTS = 5;
    public const CATEGORY_MISC = 6;
    public const CATEGORY_TEMPLATES = 7;
    public const CATEGORY_PROJECTS = 8;
    public const CATEGORY_DEMOS = 9;
    public const CATEGORY_MAX = 10;

    /**
     * Assets that are part of the Add-ons category type will be displayed
     * in the editor's AssetLib tab.
     */
    public const CATEGORY_TYPE_ADDONS = 0;

    /**
     * Assets that are part of the Projects category type will be displayed
     * in the Project Manager's Templates tab.
     */
    public const CATEGORY_TYPE_PROJECTS = 1;

    /**
     * The primary key associated with the table.
     * This value has been changed from the default for compatibility with the
     * existing asset library API.
     *
     * @var string
     */
    protected $primaryKey = 'asset_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'author_id',
        'category_id',
        'cost',
        'godot_version',
        'support_level',
        'browse_url',
        'download_url',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'support_level_id',
        'created_at',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'category',
        'support_level',
    ];

    /**
     * Get the user that posted the asset.
     */
    public function author()
    {
        return $this->belongsTo('App\User', 'author_id');
    }

    /**
     * Return the given support level's name.
     */
    public static function getSupportLevelName(int $supportLevel): string
    {
        switch ($supportLevel) {
            case self::SUPPORT_LEVEL_TESTING:
                return 'Testing';
                break;
            case self::SUPPORT_LEVEL_COMMUNITY:
                return 'Community';
                break;
            case self::SUPPORT_LEVEL_OFFICIAL:
                return 'Official';
                break;
            default:
                throw new \Exception("Invalid support level: $supportLevel");
                break;
        }
    }

    /**
     * Return the given category's name.
     */
    public static function getCategoryName(int $category): string
    {
        switch ($category) {
            case self::CATEGORY_2D_TOOLS:
                return '2D Tools';
                break;
            case self::CATEGORY_3D_TOOLS:
                return '3D Tools';
                break;
            case self::CATEGORY_SHADERS:
                return 'Shaders';
                break;
            case self::CATEGORY_MATERIALS:
                return 'Materials';
                break;
            case self::CATEGORY_TOOLS:
                return 'Tools';
                break;
            case self::CATEGORY_SCRIPTS:
                return 'Scripts';
                break;
            case self::CATEGORY_MISC:
                return 'Misc';
                break;
            case self::CATEGORY_TEMPLATES:
                return 'Templates';
                break;
            case self::CATEGORY_PROJECTS:
                return 'Projects';
                break;
            case self::CATEGORY_DEMOS:
                return 'Demos';
                break;
            default:
                throw new \Exception("Invalid category: $category");
                break;
        }
    }

    /**
     * Non-static variant of `getCategoryName()` (used in serialization).
     */
    public function getCategoryAttribute(): string
    {
        return self::getCategoryName($this->category_id);
    }

    /**
     * Non-static variant of `getSupportLevelName()` (used in serialization).
     */
    public function getSupportLevelAttribute(): string
    {
        return self::getSupportLevelName($this->support_level_id);
    }

    /**
     * Returns the given category's type.
     */
    public static function getCategoryType(int $category): int
    {
        switch ($category) {
            case self::CATEGORY_TEMPLATES:
            case self::CATEGORY_PROJECTS:
            case self::CATEGORY_DEMOS:
                return self::CATEGORY_TYPE_PROJECTS;
                break;
            default:
                if ($category >= 0 && $category < self::CATEGORY_MAX) {
                    return self::CATEGORY_TYPE_ADDONS;
                }

                throw new \Exception("Invalid category: $category");
                break;
        }
    }

    /**
     * Filter and sort the list of assets according to user-specified parameters.
     * This function doesn't perform any validation. The data passed must be
     * validated first!
     *
     * TODO: Implement more search filters.
     *
     * @see App\Http\Requests\ListAssets
     */
    public function scopeFilterSearch($query, array $validated): Collection
    {
        if (isset($validated['category'])) {
            $query->where('category', $validated['category']);
        }

        if (isset($validated['user'])) {
            $queryAuthorId = User::where('name', $validated['user'])->first()['id'];
            $query->where('author_id', $queryAuthorId);
        }

        if (isset($validated['godot_version'])) {
            $query->where('godot_version', $validated['godot_version']);
        }

        if (isset($validated['filter'])) {
            // Search anywhere in the asset's title
            $query->where('title', 'like', '%'.$validated['filter'].'%');
        }

        // Filtering must be done above

        $result = $query->get();

        // Sorting must be done below

        if (isset($validated['reverse'])) {
            $result = $result->reverse()->values();
        }

        return $result;
    }
}
