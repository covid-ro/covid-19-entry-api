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
 * @property string $phone_number
 * @property DateTime| $created_at
 * @property DateTime| $updated_at
 * @property DateTime|null $deleted_at
 */
class PhoneCode extends Model
{
    use SoftDeletes;

    /**
     * @return int
     */
    public static function generateCode(): int
    {
        return random_int(100000, 999999);
    }
}
