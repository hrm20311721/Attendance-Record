<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Record extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'kid_id',
        'do_guardian_id',
        'do_time',
        'pu_plan_guardian_id',
        'pu_plan_hour',
        'pu_plan_minute',
        'pu_guardian_id',
        'pu_time'
    ];

    protected $dates = [
        'do_time', 'pu_time'
    ];

    public function kid()
    {
        return $this->belongsTo(Kid::class);
    }

    public function do_guardian()
    {
        return $this->belongsTo(Guardian::class,'do_guardian_id');
    }

    public function pu_guardian()
    {
        return $this->belongsTo(Guardian::class, 'pu_guardian_id');
    }

    public function pu_plan_guardian()
    {
        return $this->belongsTo(Guardian::class, 'pu_plan_guardian_id');
    }

    public function getRecordsToday()
    {
        /**
        $today = date('Y-m-d');
        return $this->whereDate('created_at','=',$today)->whereTime('created_at','<=','23:59:59')->paginate(30);
        */
        return $this->paginate(30);
    }

    public function recordUpdate(Int $record_id, Array $data)
    {

        $record = Record::where('id', $record_id)->find($record_id);
        $record->do_guardian_id = $data['do_guardian_id'];
        $record->do_time        = $data['do_time'];
        $record->pu_guardian_id = $data['pu_guardian_id'];
        $record->pu_time        = $data['pu_time'];
        $record->save();
        return;
    }

    public function recordDestroy(Int $record_id)
    {
        return $this->where('id', $record_id)->delete();
    }

    public function recordStore(Array $data)
    {
        $this->kid_id = $data['kid_id'];
        $this->do_guardian_id = $data['do_guardian_id'];
        $this->do_time = $data['do_time'];
        $this->pu_plan_guardian_id = $data['pu_plan_guardian_id'];
        $this->pu_plan_hour = $data['pu_plan_hour'];
        $this->pu_plan_minute = $data['pu_plan_minute'];
        $this->save();
        return;
    }

    public function recordLeave(Int $kid_id, Array $data)
    {
        $today = date('Y-m-d');
        $record = Record::whereDate('do_time',$today)->where('kid_id',$kid_id)->first();
        $record->pu_guardian_id = $data['pu_guardian_id'];
        $record->pu_time        = $data['pu_time'];
        $record->save();
        return;
    }
}
