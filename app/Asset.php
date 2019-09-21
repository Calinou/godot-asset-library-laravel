<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Asset extends Model
{
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
     * The category type that will be displayed in the editor's AssetLib tab.
     */
    public const CATEGORY_TYPE_ADDONS = 0;

    /**
     * The category type that will be displayed in the Project Manager's Templates tab.
     */
    public const CATEGORY_TYPE_PROJECTS = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'author',
        'category',
        'cost',
        'godot_version',
        'support_level',
        'browse_url',
        'download_url',
    ];

    /**
     * Returns the given support level's name.
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
                throw new \Exception('Invalid support level.');
                break;
        }
    }

    /**
     * Returns the given category's name.
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
                throw new \Exception('Invalid category.');
                break;
        }
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

                throw new \Exception('Invalid category.');
                break;
        }
    }

    /**
     * Filter and sort the list of assets according to user-specified parameters.
     *
     * TODO: Implement more search filters.
     */
    public function scopeFilterSearch($query, Request $request): Collection
    {
        if ($request->has('user')) {
            $query->where('author', $request->input('user'));
        }

        $result = $query->get();

        if ($request->has('reverse')) {
            $result = $result->reverse()->values();
        }

        return $result;
    }
}
