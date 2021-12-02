<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kid extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'grade_id'
    ];

    public function grade()
    {
        return $this->belongsTo(Grade::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function guardians()
    {
        return $this->hasMany(Guardian::class);
    }

    public function records()
    {
        return $this->hasMany(Record::class);
    }

    public function getAllKids()
    {
        $all_kids = $this->with(['grade','guardians','lessons']); //gradesテーブルと結合
        $all_kids = $all_kids->orderBy('id')->paginate(20);
        return $all_kids;
    }

    public function getKidsInGrade(Int $grade_id)
    {
        $kids = $this->where('grade_id', $grade_id)->orderBy('id','asc')->paginate(20);
        return $kids;
    }

}
