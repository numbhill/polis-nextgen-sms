<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialHold extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

}
