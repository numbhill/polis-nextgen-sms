<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseOffering extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    // allow mass-assignment if not already present
    protected $guarded = [];

    // 👇 Let Laravel convert arrays <-> JSONB for this column
    protected $casts = [
        'schedule_meta' => 'array',
        'is_active' => 'boolean',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function professor(): BelongsTo
    {
        return $this->belongsTo(Professor::class);
    }

    public function meetings(): HasMany
    {
        return $this->hasMany(ClassMeeting::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }

}
