<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Validator extends Model
{
    protected $guarded = [];
    
    public function validation() {
        return $this->hasMany(Validation::class);
    }
    public function user() {
        return $this->belongsTo(User::class);
    }

}
