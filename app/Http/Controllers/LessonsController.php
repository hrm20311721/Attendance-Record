<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\Kid;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class LessonsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request,Kid $kid)
    {
        $kid_id = $request->kid_id;
        $kid = $kid->getAllKids()->where('id',$kid_id)->first();
        return $kid;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request,Lesson $lesson)
    {
        $data = $request->only(['kid_id','name','schedule','pu_plan_guardian_id','pu_hour','pu_minute']);
        $validator = Validator::make($data,[
            'name' => ['required','string','max:20'],
            'schedule' => ['required','integer','between:1,6'],
            'pu_plan_guardian_id' => ['required','integer'],
            'pu_hour' => ['required','integer','between:9,19'],
            'pu_minute' => ['required', 'regex:/^[0-9]{2}$/i','between:0,59']
        ]);

        $validator->validate();
        $lesson->storeLesson($data);
        return response()->json();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function show(Lesson $lesson)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function edit(Lesson $lesson,Kid $kid)
    {
        $lesson = $lesson->getEditLesson($lesson->id);
        $kid = $kid->getAllKids()->where('id',$lesson->kid_id)->first();
        return ['kid'=>$kid,'lesson'=>$lesson];
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lesson $lesson)
    {
        $data = $request->only(['name','schedule','pu_plan_guardian_id','pu_hour','pu_minute']);
        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:20'],
            'schedule' => ['required', 'integer', 'between:1,6'],
            'pu_plan_guardian_id' => ['required', 'integer'],
            'pu_hour' => ['required', 'integer', 'between:9,19'],
            'pu_minute' => ['required','regex:/^[0-9]{2}$/i', 'max:59']
        ]);

        $validator->validate();
        $lesson->updateLesson($lesson->id,$data);
        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lesson $lesson)
    {
        $lesson->destroyLesson($lesson->id);
        return response()->json();
    }
}
