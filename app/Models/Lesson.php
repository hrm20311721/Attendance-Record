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

    public function storeLesson(Array $data)
    {
        $this->kid_id = $data['kid_id'];
        $this->name = $data['name'];
        $this->schedule = $data['schedule'];
        $this->pu_plan_guardian_id = $data['pu_plan_guardian_id'];
        $this->pu_hour = $data['pu_hour'];
        $this->pu_minute = $data['pu_minute'];
        $this->save();
        return;
    }

    public function getEditLesson(Int $lesson_id)
    {
        return $this->where('id',$lesson_id)->first();
    }

    public function updateLesson(Int $lesson_id, Array $data)
    {
        $lesson = Lesson::find($lesson_id);
        $lesson->name = $data['name'];
        $lesson->schedule = $data['schedule'];
        $lesson->pu_plan_guardian_id = $data['pu_plan_guardian_id'];
        $lesson->pu_hour = $data['pu_hour'];
        $lesson->pu_minute = $data['pu_minute'];
        $lesson->save();
        return;
    }

    public function destroyLesson(Int $lesson_id)
    {
        return $this->where('id',$lesson_id)->delete();
    }

}
