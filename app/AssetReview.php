<?php

declare(strict_types=1);

namespace App;

use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * An asset review published by an user (called "author" in this case).
 * Its optional comment can use Markdown formatting. An user may only leave
 * one review per asset.
 *
 * @property int $id The review's unique ID.
 * @property bool $is_positive If `true`, the review is positive. If `false`, the review is negative.
 * @property ?string $comment The review's comment in Markdown format.
 * @property ?string $html_comment The review's comment in HTML format (generated from the Markdown source).
 * @property \Illuminate\Support\Carbon $created_at The review's creation date.
 * @property \Illuminate\Support\Carbon $updated_at The review's last modification date.
 * @property int $asset_id The ID of the asset being reviewed.
 * @property int $author_id The review's author ID.
 */
class AssetReview extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'is_positive',
        'comment',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'asset_id',
        'author_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_positive' => 'boolean',
    ];

    /**
     * Get the asset which is the subject of the review.
     */
    public function asset(): BelongsTo
    {
        return $this->belongsTo('App\Asset', 'asset_id');
    }

    /**
     * Get the user that posted the review.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo('App\User', 'author_id');
    }

    /**
     * Get the comment reply by the asset author.
     */
    public function reply(): HasOne
    {
        return $this->hasOne('App\AssetReviewReply');
    }

    /**
     * Set the comment, render the Markdown and save the rendered comment.
     * This way, the source Markdown only has to be rendered once
     * (instead of being rendered every time a page is displayed).
     */
    public function setCommentAttribute(string $comment = null): void
    {
        if ($comment) {
            $this->attributes['comment'] = $comment;
            $this->attributes['html_comment'] = Markdown::convertToHtml($comment);
        } else {
            $this->attributes['comment'] = null;
            $this->attributes['html_comment'] = null;
        }
    }
}
