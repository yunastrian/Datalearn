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
        $course = DB::table('courses')->where('id', $id_course)->first();
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

        return view('course', ['course' => $course, 'scores' => $scores, 'topics' => $topics, 'students' => $students, 'teacher' => $teacher]);
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
            'id_course' => $id
        ]);
        return redirect()->route('home', ['msg' => 2]);
    }

    /**
     * delete course
     *
     * @return msg
     */
    public function delete($id_course)
    {
        $enrolled_id = DB::table('user_course')->where('id_course', $id_course)->pluck('id_user');
        $topics = DB::table('courses')->where('id', $id_course)->get();
        foreach($topics as $topic) {
            DB::table('spreadsheets')->where('id', $topic->id)->delete();
            DB::table('grades')->where('id_topic', $topic->id)->delete();
            DB::table('topics')->where('id', $topic->id)->delete();
        }

        DB::table('user_course')->where('id_course', $id_course)->delete();
        DB::table('courses')->where('id', $id_course)->delete();
        
        return redirect()->route('home', ['msg' => 4]);
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
            'id_course' => $request->enroll_id
        ]);
        return redirect()->route('home', ['msg' => 3]);
    }

    public function editTopic($id_course, $id_topic, Request $request) {
        DB::table('topics')->where('id', $id_topic)->update([
            'name' => $request->topic_name,
            'description' => $request->topic_description
        ]);

        return redirect()->route('course', ['id_course' => $id_course, 'msg' => 4]);
    }
}
