<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class PhoneCode
 * @package App
 *
 * @property int $id
 * @property int $code
 * @property string $country_prefix
 * @property string $country_code
 * @property string $phone_number
 * @property string $formatted_phone_number
 * @property string $phone_identifier
 * @property string $notes
 * @property string $status
 * @property DateTime| $created_at
 * @property DateTime| $updated_at
 * @property DateTime|null $deleted_at
 */
class PhoneCode extends Model
{
    use SoftDeletes;

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';

    /**
     * @return int
     */
    public static function generateCode(): int
    {
        return 666666;
        //return random_int(100000, 999999); // TODO: uncomment me!!!
    }
}
