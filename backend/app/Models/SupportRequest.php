<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

/**
 * Class SupportRequest
 *
 * @property int $id
 * @property int $telegram_user_id
 * @property string $message
 * @property string $response
 * @property int $type
 * @property int $status
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 */
class SupportRequest extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'message',
        'response',
        'type',
        'status',
        'telegram_user_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            TelegramUser::class,
            'telegram_user_id',
            'telegram_id'
        );
    }
}
