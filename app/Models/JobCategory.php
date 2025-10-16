<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobCategory extends Model
{
    protected $guarded = [];

    public function jobVacancies() {
        return $this->hasMany(JobVacancy::class);
    }
    public function validations() {
        return $this->hasMany(Validation::class);
    }
}
