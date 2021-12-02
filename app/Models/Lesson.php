<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'kid_id',
        'name',
        'schedule',
        'pu_plan_guardian_id',
        'pu_hour',
        'pu_minute'
    ];

    public function kid()
    {
        return $this->belongsTo(Kid::class);
    }

    public function getLessons(Int $kid_id)
    {
        $lessons = $this->where('kid_id', $kid_id)->get();
        return $lessons;
    }

}
