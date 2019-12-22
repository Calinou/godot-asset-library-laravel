<?php

declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * An externally-hosted asset preview (can be an image or a video).
 * It can optionally have a caption defined.
 */
class AssetPreview extends Model
{
    public const TYPE_IMAGE = 0;
    public const TYPE_VIDEO = 1;
    public const TYPE_MAX = 2;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The primary key associated with the table.
     * This value has been changed from the default for compatibility with the
     * existing asset library API.
     *
     * @var string
     */
    protected $primaryKey = 'preview_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type_id',
        'link',
        'thumbnail',
        'caption',
        'asset_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'preview_id',
        'type_id',
        'asset_id',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'type',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'type_id' => 'integer',
    ];

    /**
     * Return the given type's name.
     */
    public static function getType(int $type): string
    {
        $typeNames = [
            self::TYPE_IMAGE => 'image',
            self::TYPE_VIDEO => 'video',
        ];

        if (array_key_exists($type, $typeNames)) {
            return $typeNames[$type];
        } else {
            throw new \Exception("Invalid asset preview type: $type");
        }
    }

    /**
     * Non-static variant of `getType` (used in serialization).
     */
    public function getTypeAttribute(): string
    {
        return self::getType($this->type_id);
    }
}
