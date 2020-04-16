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

        $scores = [];
        foreach($topics as $topic) {
            $grades = DB::table('grades')->where([
                ['id_topic', '=' ,$topic->id], 
                ['id_user', '=', Auth::id()]            
            ])->first();
            
            if (empty($grades)) {
                $scores[] = '-';
            } else {
                $scores[] = $grades->grade;
            }
        }

        return view('course', ['scores' => $scores, 'topics' => $topics, 'students' => $students, 'teacher' => $teacher]);
    }

    /**
     * Create new course
     *
     * @return newCourse
     */
    public function new(Request $request)
    {
        $id = DB::table('courses')->insertGetId([
            'name' => $request->course_name,
            'description' => $request->course_description,
        ]);

        DB::table('user_course')->insert([
            'id_user' => Auth::id(),
            'id_course' => $id,
            'role' => 1
        ]);
        return redirect()->route('home', ['msg' => 2]);
    }

    /**
     * Create new course
     *
     * @return newCourse
     */
    public function enroll(Request $request)
    {
        DB::table('user_course')->insert([
            'id_user' => Auth::id(),
            'id_course' => $request->enroll_id,
            'role' => 0
        ]);
        return redirect()->route('home', ['msg' => 3]);
    }
}
