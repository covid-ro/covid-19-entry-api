<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * Class EmailCode
 * @package App
 *
 * @property int $id
 * @property int $code
 * @property string $email
 * @property string $phone_identifier
 * @property string $notes
 * @property string $status
 * @property DateTime| $created_at
 * @property DateTime| $updated_at
 * @property DateTime|null $deleted_at
 */
class EmailCode extends Model implements Auditable
{
    use \OwenIt\Auditing\Auditable;
    use SoftDeletes;

    const STATUS_ACTIVE = 'active';
    const STATUS_INACTIVE = 'inactive';
}
