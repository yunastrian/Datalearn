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
     * Edit course name
     *
     * @return newCourse
     */
    public function edit($id_course, Request $request)
    {
        DB::table('courses')->where('id', $id_course)->update([
            'name' => $request->course_name,
        ]);

        return redirect()->route('course', ['id_course' => $id_course, 'msg' => 5]);
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

    /**
     * Edit Topic
     *
     * @return msg
     */
    public function editTopic($id_course, $id_topic, Request $request) {
        DB::table('topics')->where('id', $id_topic)->update([
            'name' => $request->topic_name,
            'description' => $request->topic_description
        ]);

        return redirect()->route('course', ['id_course' => $id_course, 'msg' => 4]);
    }

    /**
     * Show grade
     *
     * @return grade
     */
    public function grade($id_course) {
        if (Auth::user()->role == 0) {
            return redirect()->route('course', ['id_course' => $id_course, 'msg' => 6]);
        }

        $enrolled_id = DB::table('user_course')->where('id_course', $id_course)->pluck('id_user');

        $topics = DB::table('topics')->where('id_course', $id_course)->get();
        $name = [];
        $ids = [];
        $grades = [];
        foreach($enrolled_id as $id) {
            $user = DB::table('users')->where('id', $id)->first();

            if ($user->role == 0) {
                $name[] = $user->name;
                $ids[] = $user->id;

                $usergrade = [];
                foreach($topics as $topic) {
                    $grade = DB::table('grades')->where([
                        ['id_course', '=' ,$id_course], 
                        ['id_user', '=', $user->id],
                        ['id_topic', '=', $topic->id]
                    ])->first();

                    if (empty($grade)) {
                        $usergrade[] = '-';
                    } else {
                        $usergrade[] = $grade->grade;
                    }
                }

                $grades[] = $usergrade;
            }
        }

        return view('grade', ['topics' => $topics, 'names' => $name, 'grades' => $grades, 'id_course' => $id_course]);
    }
}
