<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Class Dua
 *
 * @property int $id
 * @property string $title
 * @property string $content
 * @property string $arabic_text
 * @property string $translation
 * @property string $transliteration
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Dua extends Model
{
    protected $fillable = [
        'title',
        'content',
        'arabic_text',
        'translation',
        'transliteration'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            Category::class
        );
    }
}
