<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;


class PbAPIController extends Controller
{
    public function login(){
        $api_hostname = '103.251.120.243';
        $api_login = 'shaun';
        $api_password = '@##$#gurdianofadn461*#?';

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
        return $session_id;
    }
    public function CurrencyList(){

        $api_hostname = '103.251.120.243';
        $api_login = 'shaun';
        $api_password = '@##$#gurdianofadn461*#?';

        $verify_hostname = false;

        $api_url = "https://$api_hostname/rest";
        $session_id = $this->login();

        $post_data = array(
            'auth_info' => json_encode(array('session_id' => $session_id)),
            'params' => json_encode( array('login' => $api_login,
            'password' => $api_password) ),
        );
        
        $curl = curl_init();

        curl_setopt_array($curl,
            array(
                CURLOPT_URL => $api_url .
                '/Currency/get_currency_list',
                CURLOPT_SSL_VERIFYPEER => $verify_hostname,
                CURLOPT_SSL_VERIFYHOST => $verify_hostname,
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
        exit;

         return $data;
        
    }


    public function makePayment($amount){
        $session_id = $this->login();
        $api_hostname = '103.251.120.243';
        $verify_hostname = false;
        $verify_hostname = false;

        $api_url = "https://$api_hostname/rest";
        $post_data = array(
            'auth_info' => json_encode(array('session_id' => $session_id)),
            'params' => json_encode( array('action' => 'Manual payment',
            'amount' => $amount,
            'internal_comment' => 'Customer Payment',
            'i_customer' => 1719, ),
        ));

        $curl = curl_init();
        curl_setopt_array($curl,
        array(
            CURLOPT_URL => $api_url . '/Customer/make_transaction',
            CURLOPT_SSL_VERIFYPEER => $verify_hostname,
            CURLOPT_SSL_VERIFYHOST => $verify_hostname,
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POSTFIELDS => http_build_query($post_data),
        ) );


        $reply = curl_exec($curl);

        // return json_decode($reply);
        
        if(! $reply) {
            //echo curl_error($curl);
            curl_close($curl);
            exit;
        }
        // return view('admin.index',)
        $data = json_decode($reply);
        // return $data->balance;
        $orders = Order::all();
        
        return view('admin.index',compact('data','orders'));

        // return view('admin.index')->with('data',$data);
        // return $data;
        
    }



}
