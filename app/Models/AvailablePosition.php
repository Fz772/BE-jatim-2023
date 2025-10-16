<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AvailablePosition extends Model
{
    protected $guarded = [];

    public function jobApplyPositions() {
        return $this->hasMany(JobApplyPosition::class, 'position_id');
    }
    public function jobVacancy() {
        return $this->belongsTo(JobVacancy::class);
    }

}
