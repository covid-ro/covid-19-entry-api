<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

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
 * @property string|null $email
 * @property string $cnp
 * @property bool $is_romanian
 * @property DateTime|null $birth_date
 * @property string|null $sex
 * @property string|null $document_type
 * @property string|null $document_series
 * @property string|null $document_number
 * @property string $travelling_from_country_code
 * @property string|null $travelling_from_city
 * @property DateTime|null $travelling_from_date
 * @property DateTime|null $home_country_return_date
 * @property bool $home_isolated
 * @property string|null $home_address
 * @property string|null $travel_route
 * @property bool|null $q_visited
 * @property bool|null $q_contacted
 * @property bool|null $q_hospitalized
 * @property string|null $vehicle_type
 * @property string|null $vehicle_registration_no
 * @property string $status
 * @property DateTime|null $border_crossed_at
 * @property DateTime|null $border_validated_at
 * @property DateTime|null $border_viewed_at
 * @property DateTime|null $dsp_validated_at
 * @property string|null $dsp_user_name
 * @property string|null $dsp_measure
 * @property bool $accept_personal_data
 * @property bool $accept_read_law
 * @property DateTime|null $created_at
 * @property DateTime|null $updated_at
 * @property DateTime|null $deleted_at
 */
class Declaration extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    const STATUS_PHONE_VALIDATED = 'phone_validated';
    const STATUS_BORDER_VALIDATED = 'border_validated';
    const STATUS_BORDER_INVALID = 'border_invalid';
    const STATUS_DSP_VALIDATED = 'dsp_validated';

    const AVAILABLE_STATUS_LIST = [
        self::STATUS_PHONE_VALIDATED,
        self::STATUS_BORDER_VALIDATED,
        self::STATUS_BORDER_INVALID,
        self::STATUS_DSP_VALIDATED
    ];

    const STATUS_LIST = [
        self::STATUS_PHONE_VALIDATED => 'Phone Validated',
        self::STATUS_BORDER_INVALID => 'Border Invalid',
        self::STATUS_BORDER_VALIDATED => 'Border Validated',
        self::STATUS_DSP_VALIDATED => 'DSP Validated'
    ];

    protected $dates = [
        'created_at',
        'border_crossed_at',
        'border_validated_at',
        'border_viewed_at',
        'dsp_validated_at'
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
        $declarationData['email'] = $this->email ?? null;
        $declarationData['cnp'] = $this->cnp;
        $declarationData['is_romanian'] = (bool)$this->is_romanian;
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

        $declarationData['home_isolated'] = (bool)$this->home_isolated;
        $declarationData['home_address'] = $this->home_address;

        $declarationData['isolation_addresses'] = [];

        if (empty($this->home_isolated)) {
            /** @var IsolationAddress $isolationAddress */
            foreach ($this->isolationaddresses()->get() as $isolationAddress) {
                $declarationData['isolation_addresses'][] = $isolationAddress->toArray();
            }
        }

        $declarationData['q_visited'] = !is_null($this->q_visited) ? (bool)$this->q_visited : null;
        $declarationData['q_contacted'] = !is_null($this->q_contacted) ? (bool)$this->q_contacted : null;
        $declarationData['q_hospitalized'] = !is_null($this->q_hospitalized) ? (bool)$this->q_hospitalized : null;

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
        $declarationData['border_viewed_at'] = !empty($this->border_viewed_at) ? $this->border_viewed_at->format(DateTime::ISO8601) : null;
        $declarationData['dsp_validated_at'] = !empty($this->dsp_validated_at) ? $this->dsp_validated_at->format(DateTime::ISO8601) : null;
        $declarationData['dsp_user_name'] = $this->dsp_user_name;
        $declarationData['dsp_measure'] = $this->dsp_measure;
        $declarationData['accept_personal_data'] = (bool)$this->accept_personal_data;
        $declarationData['accept_read_law'] = (bool)$this->accept_read_law;

        return $declarationData;
    }
}
