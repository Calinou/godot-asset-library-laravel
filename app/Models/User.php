<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * A registered user. Users can publish assets and submit reviews (except for their
 * own assets).
 *
 * @property int $id The user's unique ID.
 * @property string $username The user's username (must be unique). Can only contain alphanumeric characters, underscores and dashes. Must not begin with a number, underscore or dash.
 * @property ?string $full_name The user's full name.
 * @property string $email The user's email address (must be unique).
 * @property ?\Illuminate\Support\Carbon $email_verified_at The user's email address verficiation date. If not set, the user's email address isn't verified.
 * @property ?string $password The user's hashed password. Can be empty if the user authenticated using OAuth2.
 * @property ?string $provider The user's OAuth2 provider name (if any).
 * @property ?string $provider_id The user's OAuth2 provider's unique ID (may be used if the provider supports other means of logging in than an email).
 * @property bool $is_admin If `true`, the user is an administrator.
 * @property bool $is_blocked If `true`, the user is blocked and can't submit assets or reviews. However, their existing content remains visible and they can still browse the asset library.
 * @property string $remember_token The user's randomly-generated token for the "remember me" function.
 * @property \Illuminate\Support\Carbon $created_at The user's creation date.
 * @property \Illuminate\Support\Carbon $updated_at The user's last modification date.
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The maximum username length (in characters). If this value is changed,
     * it will only apply to users registering after the change has been made.
     */
    public const USERNAME_MAX_LENGTH = 20;

    /**
     * The maximum full name length (in characters). If this value is changed,
     * it will only apply to users registering after the change has been made.
     */
    public const FULL_NAME_MAX_LENGTH = 30;

    /**
     * The minimum password length (in characters). If this value is changed,
     * it will only apply to users registering after the change has been made.
     */
    public const PASSWORD_MIN_LENGTH = 10;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'full_name',
        'email',
        'password',
        'provider',
        'provider_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'is_admin',
        'is_blocked',
        'provider',
        'provider_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'is_blocked' => 'boolean',
    ];

    /**
     * Get the user's assets.
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'author_id');
    }

    /**
     * Get the user's reviews.
     */
    public function assetReviews(): HasMany
    {
        return $this->hasMany(AssetReview::class, 'author_id');
    }

    /**
     * Converts the model to a string representation (used for logging purposes).
     */
    public function __toString(): string
    {
        return "$this->username (#$this->id)";
    }
}
