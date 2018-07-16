<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class User
 * @package App\Models
 *
 * Fillable properties
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    const PROPERTY_ID = 'id';
    const PROPERTY_FIRST_NAME = 'first_name';
    const PROPERTY_LAST_NAME = 'last_name';
    const PROPERTY_EMAIL = 'email';
    const PROPERTY_PASSWORD = 'password';
    const PRIMARY_KEY = self::PROPERTY_ID;

    protected $table = 'users';

    protected $fillable = [
        self::PRIMARY_KEY,
        self::PROPERTY_FIRST_NAME,
        self::PROPERTY_LAST_NAME,
        self::PROPERTY_EMAIL,
        self::PROPERTY_PASSWORD
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        self::PROPERTY_PASSWORD
    ];
}
