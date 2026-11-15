<?php

namespace App\Models;

use Database\Factories\ReplyFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QaReply extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'user_id',
        'qa_thread_id',
        'body',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function thread(): BelongsTo
    {
        return $this->belongsTo(QaThread::class);
    }
}
