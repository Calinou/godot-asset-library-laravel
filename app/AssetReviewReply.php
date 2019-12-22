<?php

declare(strict_types=1);

namespace App;

use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Database\Eloquent\Model;

/**
 * An asset author's reply to an asset review. Its optional comment can use
 * Markdown formatting. An author may only reply once to each review, and
 * the reply can't be replied to by another user.
 */
class AssetReviewReply extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'comment',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'asset_review_id',
    ];

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
        }
    }
}
