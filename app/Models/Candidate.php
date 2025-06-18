<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = ['name', 'email', 'description', 'status', 'phone', 'organization_name'];

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }
}
