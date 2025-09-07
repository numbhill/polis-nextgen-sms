<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

}
