<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{

    protected $fillable = [
        'goal_id',
        'completed_at',
    ];


    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }
}
