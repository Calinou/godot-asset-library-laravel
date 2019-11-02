<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GrahamCampbell\Markdown\Facades\Markdown;

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
     * Get the user that posted the review.
     */
    public function author()
    {
        return $this->belongsTo('App\User', 'author_id');
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
        }
    }

    /**
     * Set the comment reply, render the Markdown and save the rendered comment reply.
     * This way, the source Markdown only has to be rendered once
     * (instead of being rendered every time a page is displayed).
     */
    public function setCommentReplyAttribute(string $commentReply): void
    {
        $this->attributes['comment_reply'] = $commentReply;
        $this->attributes['html_comment_reply'] = Markdown::convertToHtml($commentReply);
    }
}
