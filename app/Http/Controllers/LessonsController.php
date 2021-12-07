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
        $kid_id = $request->kid_id;
        $validator = Validator::make($data,[
            'name' => ['required','string','max:20'],
            'schedule' => ['required','integer','between:1,6'],
            'pu_plan_guardian_id' => ['required','integer'],
            'pu_hour' => ['required','integer','between:9,19'],
            'pu_minute' => ['required','integer','between:0,59']
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
    public function edit(Lesson $lesson)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lesson  $lesson
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lesson $lesson)
    {
        //
    }
}
