<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplyPosition extends Model
{
    protected $guarded = [];
    public $timestamps = false;

    public function society() {
        return $this->belongsTo(Society::class, 'society_id', 'id');
    }
    public function jobVacancy() {
        return $this->belongsTo(JobVacancy::class, 'job_vacancy_id', 'id');
    }
    public function position() {
        return $this->belongsTo(AvailablePosition::class, 'position_id', 'id');
    }
    public function jobApplySociety() {
        return $this->belongsTo(JobApplySociety::class, 'job_apply_societies_id', 'id');
    }
}
