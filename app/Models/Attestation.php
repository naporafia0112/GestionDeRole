<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attestation extends Model
{
    protected $fillable = ['stage_id', 'type', 'service', 'debut', 'fin', 'date_generation'];

    public function stage()
    {
        return $this->belongsTo(Stage::class, 'stage_id');
    }

}
