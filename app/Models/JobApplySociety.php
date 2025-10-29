<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplySociety extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    public function society() {
        return $this->belongsTo(Society::class, 'society_id', 'id');
    }
    public function jobVacancy() {
        return $this->belongsTo(JobVacancy::class, 'job_vacancy_id', 'id');
    }
    public function jobApplyPositions(){
        return $this->hasMany(JobApplyPosition::class, 'job_apply_societies_id');
    }
}
