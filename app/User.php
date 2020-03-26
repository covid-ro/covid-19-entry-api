<?php

namespace App;

use DateTime;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Lumen\Auth\Authorizable;

/**
 * Class User
 * @package App
 *
 * @property int $id
 * @property string $phone_number
 * @property string $country_code
 * @property string $token
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 * @property DateTime|null $deleted_at
 */
class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable, SoftDeletes;

    const DOCUMENT_TYPE_IDENTITY_CARD = 'identity_card';
    const DOCUMENT_TYPE_PASSPORT = 'passport';

    const VEHICLE_TYPE_AUTO = 'auto';
    const VEHICLE_TYPE_AMBULANCE = 'ambulance';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'phone_number', 'country_code',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'token',
    ];

    /**
     * @return HasMany
     */
    public function declarations()
    {
        return $this->hasMany(Declaration::class);
    }
}
