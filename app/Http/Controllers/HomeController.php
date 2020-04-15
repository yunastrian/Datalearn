<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
    public function index()
    {
        $profile = DB::table('users')->where('id', Auth::id())->first();
        $role = 'Siswa';
        if ($profile->role == 1) {
            $role = 'Pengajar';
        }

        $courses = DB::table('courses')->get();
        $teachers = [];
        $enrolled_id = DB::table('user_course')->where('id_user', Auth::id())->pluck('id_course');

        $enrolled = [];
        foreach($enrolled_id as $id) {
            $enrolled[] = DB::table('courses')->where('id', $id)->first();
        }

        foreach($courses as $course) {
            $temp = DB::table('user_course')->where([
                ['id_course', '=', $course->id],
                ['role', '=', 1]
            ])->first();
            $teacher = DB::table('users')->where('id', $temp->id_user)->first();
            $teachers[] = $teacher->name;
        }

        return view('home', ['profile' => $profile, 'role' => $role, 'courses' => $courses, 'teachers' => $teachers, 'enrolled' => $enrolled]);
    }

    public function profile(Request $request)
    {
        DB::table('users')->where('id', Auth::id())->update([
            'name' => $request->new_name,
        ]);
        
        return redirect()->route('home', ['msg' => 1]);
    }
}
