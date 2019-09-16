<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;

class Asset extends Model
{
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
        'browse_url',
        'download_url',
    ];

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
