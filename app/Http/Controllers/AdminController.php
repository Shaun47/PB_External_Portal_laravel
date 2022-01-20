<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Admin;
use Carbon\Carbon; 
use App\Http\Controllers\Hash;

class AdminController extends Controller 
{
    public function Dashboard(){
        return view('admin.index');
    }

    public function Index(){
        return view('admin.admin_login');
    }

    public function Login(Request $request){
        // dd($request->all());
        $check = $request->all();
        // dd($check['email']);
        if(Auth::guard('admin')->attempt(['email' => $check['email'], 'password' => $check['password'] ])){
            return redirect()->route('admin.dashboard')->with('success','Admin Logged in successfully!');
        }
        else{
            return redirect()->route('login_from')->with('error', 'Password Credentials did not matched!');
        }



        // if($check['email'] == 'shaun@gmail.com' && $check['password'] == 'default123'){
        //     return redirect()->route('admin.dashboard')->with('success','Admin Logged in successfully!');
        // }
    }

    public function AdminRegister(){
        return view('admin.admin_register');
    }


    public function AdminRegisterCreate(Request $request){
        // dd($request->all());
        $password = $request->password;
        $password_confirmation = $request->password_confirmation;

        if($password == $password_confirmation){
            Admin::insert([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'created_at' => Carbon::now(),
            ]);
        }
        return redirect()->route('login_from')->with('error', 'Admin created successfully!');
        
    }

    public function AdminLogout(){
        Auth::guard('admin')->logout();
        return redirect()->route('login_from')->with('error', 'You have been successfully logged out!');
    }

}
