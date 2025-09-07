<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Scholarship extends Model
{
    use HasUuids;

    public $incrementing = false;
    protected $keyType = 'string';

    public function assignments(): HasMany
    {
        return $this->hasMany(ScholarshipAssignment::class);
    }

}
