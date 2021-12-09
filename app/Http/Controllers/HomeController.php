<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Grade;
use App\Models\Kid;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Kid $kid)
    {
        $grades = Grade::all();
        $kids = $kid->getAllKids();
        return view('home',['grades' => $grades, 'kids' => $kids]);
    }

    public function procedure()
    {
        return view('procedure');
    }

    public function getKids(Request $request, Kid $kid)
    {
        $grade_id = $request['grade'];
        if($grade_id==0){
            $kids = $kid->getAllKids();
        } else {
            $kids = $kid->getKidsInGrade($grade_id);
        }

        return $kids;
    }
}
