<?php

namespace App\Models;

use App\Enums\QaThreadStatus;
use Database\Factories\ThreadFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Thread extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'user_id',
        'certification_id',
        'title',
        'body',
        'status',
    ];

    protected $casts = [
        'status' => QaThreadStatus::class,
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function certification(): BelongsTo
    {
        return $this->belongsTo(Certification::class);
    }

    public function replies():hasMany
    {
        return $this->hasMany(Reply::class);
    }

    public function scopeKeyword(Builder $query, ?string $keyword): Builder
    {
        if ($keyword === null || $keyword === '') {
            return $query;
        }

        return $query->where('body', 'LIKE', '%'.$keyword.'%');
    }

    public function scopeCertificationSearch(Builder $query, ?string $certification_id): Builder
    {
        if ($certification_id === null || $certification_id === '') {
            return $query;
        }

        return $query->where('certification_id', $certification_id);
    }
}
