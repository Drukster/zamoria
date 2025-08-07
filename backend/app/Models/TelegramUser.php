<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Class TelegramUser
 *
 * @property int $id
 * @property int $telegram_id
 * @property string $first_name
 * @property string $last_name
 * @property string $username
 * @property boolean $is_premium
 * @property boolean$is_active
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class TelegramUser extends Model
{
    protected $fillable = [
        'telegram_id',
        'first_name',
        'last_name',
        'username',
        'is_premium',
        'is_active',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
