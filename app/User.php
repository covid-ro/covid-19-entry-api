<?php

namespace App;

use DateTime;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Laravel\Lumen\Auth\Authorizable;

/**
 * Class User
 * @package App
 *
 * @property int $id
 * @property string $phone_number
 * @property string $country_code
 * @property string $token
 *
 * @property string|null $name
 * @property string|null $surname
 * @property string|null $email
 * @property string|null $cnp
 * @property string|null $document_type
 * @property string|null $document_series
 * @property string|null $document_number
 *
 * @property string|null $travelling_from_country_code
 * @property string|null $travelling_from_city
 * @property DateTime|null $travelling_from_date
 * @property DateTime $home_country_return_date
 *
 * @property string $question_1_answer
 * @property string $question_2_answer
 * @property string $question_3_answer
 *
 * @property bool $symptom_fever
 * @property bool $symptom_swallow
 * @property bool $symptom_breathing
 * @property bool $symptom_cough
 *
 * @property string|null $vehicle_type
 * @property string|null $vehicle_registration_no
 *
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
     * @return string
     */
    public static function generateToken(): string
    {
        return Str::random(32);
    }

    /**
     * @return HasMany
     */
    public function isolationAddresses()
    {
        return $this->hasMany(IsolationAddress::class);
    }

    /**
     * @return HasMany
     */
    public function itineraryCountries()
    {
        return $this->hasMany(ItineraryCountry::class);
    }

    /**
     * Resets user data
     */
    public function resetData()
    {
        $this->name = null;
        $this->surname = null;
        $this->email = null;
        $this->cnp = null;
        $this->document_type = null;
        $this->document_series = null;
        $this->document_number = null;

        $this->travelling_from_country_code = null;
        $this->travelling_from_city = null;
        $this->travelling_from_date = null;
        $this->home_country_return_date = null;

        $this->question_1_answer = null;
        $this->question_2_answer = null;
        $this->question_3_answer = null;

        $this->symptom_fever = false;
        $this->symptom_swallow = false;
        $this->symptom_breathing = false;
        $this->symptom_cough = false;

        $this->vehicle_type = null;
        $this->vehicle_registration_no = null;

        $this->isolationAddresses()->delete();
        $this->itineraryCountries()->delete();

        $this->save();
    }
}
