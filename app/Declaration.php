<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Declaration
 * @package App
 *
 * @property int $id
 * @property int $user_id
 * @property int $declarationcode_id
 * @property int $border_checkpoint_id
 * @property string $name
 * @property string $surname
 * @property string $email
 * @property string $cnp
 * @property DateTime|null $birth_date
 * @property string|null $sex
 * @property string $document_type
 * @property string $document_series
 * @property string $document_number
 * @property string $travelling_from_country_code
 * @property string $travelling_from_city
 * @property DateTime $travelling_from_date
 * @property DateTime $home_country_return_date
 * @property string|null $travel_route
 * @property bool $q_visited
 * @property bool $q_contacted
 * @property bool $q_hospitalized
 * @property string $vehicle_type
 * @property string|null $vehicle_registration_no
 * @property string $status
 * @property DateTime|null $border_crossed_at
 * @property DateTime|null $border_validated_at
 * @property DateTime|null $dsp_validated_at
 * @property string|null $dsp_user_name
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 * @property DateTime|null $deleted_at
 */
class Declaration extends Model
{
    use SoftDeletes;

    const STATUS_PHONE_VALIDATED = 'phone_validated';
    const STATUS_BORDER_VALIDATED = 'border_validated';
    const STATUS_BORDER_INVALID = 'border_invalid';
    const STATUS_DSP_VALIDATED = 'dsp_validated';

    const STATUS_LIST = [
        self::STATUS_PHONE_VALIDATED => 'Phone Validated',
        self::STATUS_BORDER_INVALID => 'Border Invalid',
        self::STATUS_BORDER_VALIDATED => 'Border Validated',
        self::STATUS_DSP_VALIDATED => 'DSP Validated'
    ];

    /**
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsTo
     */
    public function declarationcode()
    {
        return $this->belongsTo(DeclarationCode::class);
    }

    /**
     * @return BelongsTo
     */
    public function bordercheckpoint()
    {
        return $this->belongsTo(BorderCheckpoint::class, 'border_checkpoint_id');
    }

    /**
     * @return HasMany
     */
    public function isolationaddresses()
    {
        return $this->hasMany(IsolationAddress::class);
    }

    /**
     * @return HasMany
     */
    public function itinerarycountries()
    {
        return $this->hasMany(ItineraryCountry::class);
    }

    /**
     * @return HasOne
     */
    public function declarationsignature()
    {
        return $this->hasOne(DeclarationSignature::class);
    }

    /**
     * @return BelongsToMany
     */
    public function symptoms()
    {
        return $this->belongsToMany(Symptom::class);
    }

    /**
     * @return HasOne
     */
    public function dspdeclaration()
    {
        return $this->hasOne(DspDeclaration::class);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $declarationData = [];
        $declarationData['code'] = $this->declarationcode->code;
        $declarationData['phone'] = $this->user->phone_number;
        $declarationData['name'] = $this->name;
        $declarationData['surname'] = $this->surname;
        $declarationData['email'] = $this->email;
        $declarationData['cnp'] = $this->cnp;
        $declarationData['birth_date'] = $this->birth_date;
        $declarationData['sex'] = $this->sex;

        $declarationData['border_checkpoint'] = !empty($this->bordercheckpoint) ? $this->bordercheckpoint : null;
        $declarationData['document_type'] = $this->document_type;
        $declarationData['document_series'] = $this->document_series;
        $declarationData['document_number'] = $this->document_number;

        $declarationData['travelling_from_country_code'] = $this->travelling_from_country_code;
        $declarationData['travelling_from_city'] = $this->travelling_from_city;
        $declarationData['travelling_from_date'] = $this->travelling_from_date;
        $declarationData['home_country_return_date'] = $this->home_country_return_date;
        $declarationData['travel_route'] = $this->travel_route;

        $declarationData['isolation_addresses'] = [];

        /** @var IsolationAddress $isolationAddress */
        foreach ($this->isolationaddresses()->get() as $isolationAddress) {
            $declarationData['isolation_addresses'][] = $isolationAddress->toArray();
        }

        $declarationData['q_visited'] = (bool)$this->q_visited;
        $declarationData['q_contacted'] = (bool)$this->q_contacted;
        $declarationData['q_hospitalized'] = (bool)$this->q_hospitalized;

        $declarationData['symptoms'] = $this->symptoms()->pluck('name');

        $declarationData['itinerary_country_list'] = [];

        /** @var ItineraryCountry $itineraryCountry */
        foreach ($this->itinerarycountries()->get() as $itineraryCountry) {
            $declarationData['itinerary_country_list'][] = $itineraryCountry->country_code;
        }

        $declarationData['vehicle_type'] = $this->vehicle_type;
        $declarationData['vehicle_registration_no'] = $this->vehicle_registration_no;

        $declarationData['signed'] = !empty($this->declarationsignature);
        $declarationData['status'] = self::STATUS_LIST[$this->status];
        $declarationData['created_at'] = $this->created_at->format(DateTime::ISO8601);
        $declarationData['border_crossed_at'] = !empty($this->border_crossed_at) ? $this->border_crossed_at->format(DateTime::ISO8601) : null;
        $declarationData['border_validated_at'] = !empty($this->border_validated_at) ? $this->border_validated_at->format(DateTime::ISO8601) : null;
        $declarationData['dsp_validated_at'] = !empty($this->dsp_validated_at) ? $this->dsp_validated_at->format(DateTime::ISO8601) : null;
        $declarationData['dsp_user_name'] = $this->dsp_user_name;

        return $declarationData;
    }
}
