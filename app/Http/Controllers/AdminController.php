<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Admin;
use Carbon\Carbon; 
use App\Http\Controllers\Hash;
use App\Models\Order;

class AdminController extends Controller 
{
    public function Dashboard(){
        // echo "asd"; exit;
        $orders = Order::all();
        $balance = $this->customerDetail();
        return view('admin.index', compact('orders','balance'));
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


    public function customerDetail(){
        $api_hostname = '103.251.120.243';
        $api_login = 'shaun';
        $api_password = '@##$#gurdianofadn461*#?';
        # for self-signed certificates
        $verify_hostname = false;

        $api_url = "https://$api_hostname/rest";

        $post_data = array(
            'params' => json_encode(array('login' => $api_login,
            'password' => $api_password)),
        );

        $curl = curl_init();

        curl_setopt_array($curl,
            array(
                //CURLOPT_VERBOSE => true,
                CURLOPT_URL => $api_url . '/Session/login',
                CURLOPT_SSL_VERIFYPEER => $verify_hostname,
                CURLOPT_SSL_VERIFYHOST => $verify_hostname,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query($post_data),
            )
        );

        $reply = curl_exec($curl);
        if(! $reply) {
            echo curl_error($curl);
            curl_close($curl);
            exit;
        }

        $data = json_decode($reply);
        $session_id = $data->{'session_id'};





        $post_data = array(
            'auth_info' => json_encode(array('session_id' => $session_id)),
            'params' => json_encode( array(
                'detailed_info' => 1, 'i_customer' => 1719
            ) ),
        );

        // get customer info

        curl_setopt_array($curl,
            array(
                CURLOPT_URL => $api_url .
                '/Customer/get_customer_info',
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => http_build_query($post_data),
            )
        );

        $reply = curl_exec($curl);

        if(! $reply) {
            echo curl_error($curl);
            curl_close($curl);
            exit;
        }

        $data = json_decode($reply);

        

        curl_close($curl);


        return $data->customer_info->balance;
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
