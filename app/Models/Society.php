<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Society extends Model
{
    use HasApiTokens;
    protected $guarded = [];
    public $timestamps = false;
    protected $hidden = ['id', 'password', 'id_card_number', 'regional_id', 'login_tokens'];

    public function jobApplyPositions() {
        return $this->hasMany(JobApplyPosition::class);
    }
    public function jobApplySocieties() {
        return $this->hasMany(JobApplySociety::class);
    }
    public function validations() {
        return $this->hasMany(Validation::class);
    }
    public function regional() {
       return $this->belongsTo(Regional::class);
    }

}
