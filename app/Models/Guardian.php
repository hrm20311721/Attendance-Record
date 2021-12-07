<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guardian extends Model
{
    use SoftDeletes;

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

    public function guardianUpdate(Int $guardian_id, Array $data)
    {
        $guardian = Guardian::where('id',$guardian_id)->find($guardian_id);
        $guardian->relation = $data['relation'];
        $guardian->name = $data['name'];
        $guardian->save();
        return;
    }

    public function getEditGuardian(Int $guardian_id)
    {
        return $this->where('id',$guardian_id)->first();
    }

    public function destroyGuardian(Int $guardian_id)
    {
        return $this->where('id',$guardian_id)->delete();
    }

}
