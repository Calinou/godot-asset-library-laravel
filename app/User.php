<?php

declare(strict_types=1);

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The maximum name length (in characters). If this value is changed,
     * it will only apply to users registering after the change has been made.
     */
    public const NAME_MAX_LENGTH = 30;

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
        'name',
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
    ];

    /**
     * Get the user's assets.
     */
    public function assets()
    {
        return $this->hasMany('App\Asset', 'author_id');
    }
}
