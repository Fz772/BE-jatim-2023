<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Validation extends Model
{
    protected $guarded = [];

    public function society() {
        return $this->belongsTo(Society::class);
    }
    public function jobCategory() {
        return $this->belongsTo(JobCategory::class);
    }
    public function validator() {
        return $this->belongsTo(Validator::class);
    }
}
