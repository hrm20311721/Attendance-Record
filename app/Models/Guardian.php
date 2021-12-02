<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guardian extends Model
{
    use HasFactory;

    protected $fillable = [
        'relation',
        'name'
    ];

    public function kid()
    {
        return $this->belongsTo(Kid::class);
    }

    public function getGuardians($kid_id)
    {
        $guardians = $this->where('kid_id', $kid_id)->get();
        return $guardians;
    }

}
