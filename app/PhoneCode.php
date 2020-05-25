<?php

namespace App;

use App\Service\CodeGenerator;
use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

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
class PhoneCode extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
}
