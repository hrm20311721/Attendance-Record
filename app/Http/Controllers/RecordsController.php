<?php

namespace App\Http\Controllers;

use App\Models\Guardian;
use App\Models\Kid;
use App\Models\Record;
use App\Rules\OnceADay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator as ValidationValidator;


class RecordsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Record $record, Guardian $guardian)
    {
        $records = $record->getRecordsToday();
        $guardians = $guardian->getGuardians($record->kid_id);

        return view('records.index',['records' => $records, 'guardians' => $guardians]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Kid $kid, Record $record)
    {
        $kid_id = $request->kid; //ajaxでkid_idを受け取る
        $time = date('Y-m-d');
        $kid = $kid->getAllKids()->where('id', $kid_id)->first(); //kidを取得
        $record = $record->whereDate('do_time',$time)->where('kid_id', $kid_id)->get();

        return ['kid'=>$kid, 'record' => $record];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Record $record)
    {
        $data = $request->only(['kid_id','do_guardian_id','do_time','pu_plan_guardian_id', 'pu_plan_hour', 'pu_plan_minute']);
        $kid_id = $data['kid_id'];
        $validator = Validator::make($data,[
            'kid_id'                => ['required','integer'],
            'do_guardian_id'        => ['required', 'integer'],
            'do_time'               => ['required', 'date',new OnceADay($kid_id)],
            'pu_plan_guardian_id'   => ['required','integer'],
            'pu_plan_hour'          => ['required','integer','min:9','max:18'],
            'pu_plan_minute'        => ['required','min:0','max:50'],
        ]);
        $validator->validate();
        $record->recordStore($data);

        return response()->json();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Record  $record
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,Record $record)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Record  $record
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Record $record,Kid $kid)
    {
        $record_id = $request->record; //ajaxでrecord_idを受け取る
        $record = $record->find($record_id); //対象のレコードを取得
        $kid = $kid->getAllKids()->where('id',$record->kid_id)->first();

        return ['record' => $record,'kid' => $kid];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Record  $record
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Record $record)
    {

        $data = $request->only(['do_guardian_id', 'do_time', 'pu_guardian_id','pu_time']);
        $validator = Validator::make($data,[
            'do_guardian_id'        => ['required', 'integer'],
            'do_time'               => ['required', 'date'],
            'pu_guardian_id'        => ['required', 'integer'],
            'pu_time'               => ['required', 'date']
        ]);

        $validator->validate();
        $record->recordUpdate($record->id,$data);
        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Record  $record
     * @return \Illuminate\Http\Response
     */
    public function destroy(Record $record)
    {
        $record->recordDestroy($record->id);
        return response()->json();
    }

    public function leave(Request $request,Record $record)
    {
        $data = $request->only(['kid_id','pu_guardian_id','pu_time']); //リクエストを格納
        $kid_id = $data['kid_id']; //kid_idを取り出す

        //バリデーション
        $validator = Validator::make($data,[
            'kid_id'            => ['required', 'integer'],
            'pu_guardian_id'    => ['required', 'integer'],
            'pu_time'           => ['required', 'date',new OnceADay($kid_id)]
        ]);
        $validator->validate();
        $record->recordLeave($kid_id,$data);
        return response()->json();
    }
}
