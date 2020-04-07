<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
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
    public function index($id_course)
    {
        $topics = DB::table('topics')->where('id_course', $id_course)->get();
        $enrolled_id = DB::table('user_course')->where('id_course', $id_course)->pluck('id_user');

        $students = [];
        $teacher = '';
        foreach($enrolled_id as $id) {
            $temp = DB::table('users')->where('id', $id)->first();
            if ($temp->role == 1) {
                $teacher = $temp->name;
            } else {
                $students[] = $temp->name;
            }
        }
        
        return view('course', ['topics' => $topics, 'students' => $students, 'teacher' => $teacher]);
    }
}
