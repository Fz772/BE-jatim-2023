<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobVacancy extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function jobCategory() {
        return $this->belongsTo(JobCategory::class);
    }
    public function availablePositions() {
        return $this->hasMany(AvailablePosition::class);
    }
    public function jobApplySocieties() {
        return $this->hasMany(JobApplySociety::class);
    }
    public function jobApplyPositions() {
        return $this->hasMany(JobApplyPosition::class);
    }
    
}
