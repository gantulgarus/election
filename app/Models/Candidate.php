<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = ['name', 'email', 'description', 'status', 'phone'];

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}
