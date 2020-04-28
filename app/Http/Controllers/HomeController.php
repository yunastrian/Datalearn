<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdatePasswordRequest;

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

        return view('home', ['profile' => $profile, 'role' => $role, 'courses' => $courses, 'enrolled' => $enrolled]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function editPassword()
    {
        return view('editPassword');
    }

    public function profile(Request $request)
    {
        DB::table('users')->where('id', Auth::id())->update([
            'name' => $request->new_name,
        ]);
        
        return redirect()->route('home', ['msg' => 1]);
    }

    /**
     * @param UpdatePasswordRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function password(UpdatePasswordRequest $request)
    {
        DB::table('users')->where('id', Auth::id())->update([
            'password' => Hash::make($request->get('password'))
        ]);

        return redirect()->route('home', ['msg' => 5]);
    }
}
