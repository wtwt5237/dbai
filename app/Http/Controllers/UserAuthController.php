<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\User;
use Hash;
use Illuminate\Support\Facades\DB;

//use Illuminate\Support\Facades\Schema;

class UserAuthController extends Controller
{
    public function login()
    {
        return view('login/login');
    }

    public function postLogin(Request $req)
    {
        $req->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $req->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect('database')->withSuccess('Great! You have successfully logged in.');
        }

        return back()->withErrors([
            'password' => 'The provided credential does not match our records.',
        ]);
    }

    public function userManagement()
    {
        if (!Auth::check() || Auth::user()->role != 'admin') {
            return redirect('database')->with('authError', 'Oops. You don\'t have the access permission.');
        }

        return view('user/user-management');
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();

        return redirect('login');
    }

    public function curUser(Request $req)
    {
        $user_info = [];
        array_push($user_info, array(
            $req->user()->role,
            $req->user()->email
        ));
        return response()->json(["data" => $user_info]);
    }

    public function allUsers(Request $req)
    {
        $user_info = [];
        $users = DB::table('users')->select('*')->get();
        foreach ($users as $user) {
            // initial info displayed in user edit window
            $edit_info = array(
                $user->id,
                $user->role,
                $user->username,
                $user->email,
                $user->permission,
            );

            $permissions = $user->permission;
            if (empty($permissions)) $permissions = 'none';
            else if ($permissions == 'all') $permissions = 'all';
            else {
                $tmp = '';
                $pers = json_decode($permissions);
                foreach ($pers as $key => $per) {
                    $per = str_replace(['dbai_', '_'], ['', ' '], $per);
                    if ($key == 0) $tmp .= $per;
                    else $tmp .= ', ' . $per;
                }
                $permissions = $tmp;
            }

            $edit_info = json_encode($edit_info);
            if ($user->role != 'admin') {
                $delete_action = "<i class='fa-solid fa-trash user-delete' data-bs-toggle='modal' data-bs-target='#deleteModal' onclick='deleteUser($edit_info)'></i>";
            } else $delete_action = '';
            array_push($user_info, array(
                "<div><i class='fa fa-pencil me-2 user-edit' data-bs-toggle='modal' data-bs-target='#editModal' onclick='editUser($edit_info)'></i>" . $delete_action . "</div>",
                $user->role,
                $user->username,
                $user->email,
                $permissions
            ));
        }

        return response()->json(["data" => $user_info]);
    }

    public function addUser(Request $req)
    {
        DB::table('users')->insert([
            'role' => 'editor',
            'username' => $req->input('username'),
            'email' => $req->input('email'),
            'password' => Hash::make($req->input('password')),
            'permission' => 'all',
            'created_at' => now(),
        ]);
        return back();
    }

    public function updateUser(Request $req, $user_id)
    {
        $permission = [];
        foreach (config('global.db_to_display.user') as $db_name) {
            $db_name = strtolower(str_replace(' ', '', $db_name));
            if ($req->$db_name == 'on') {
                array_push($permission, $db_name);
            }
        };

        if (count(config('global.db_to_display.user')) == count($permission)) $permission = 'all';
        if (empty($permission)) $permission = NULL;

        $user = User::find($user_id);
        $user_info = array_filter($req->all());
//        foreach ($user_info as $k => $v) {
//            if ($k == '_token' or str_contains($k, 'dbai_')) {
//                unset($user_info[$k]);
//            }
//        }
        $user_info['permission'] = $permission;
        $user->update($user_info);

        return back();
    }

    public function deleteUser(Request $req, $user_id)
    {
        $user = User::find($user_id);
        $user->delete();
        return back();
    }

    public function userProfile(Request $req)
    {
        if (!Auth::check()) {
            return back()->withErrors([
                'auth' => 'Oops. You don\'t have the access permission.',
            ]);
        }
        return view('user/user-profile');
    }

}
