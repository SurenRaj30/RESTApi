<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;

class AuthController extends Controller
{
    
    public function register(Request $request){
        
        $request->validate([
            'name' => 'required|max:10',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $user = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('API Token')->accessToken;

        return response()->json(['token'=>$token, 'message'=>'New user created succesfully'], 200);
       
    }

        //Login user

        public function login(Request $request){
            
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:8',
            ]);

            $data = [
                'email' => $request->email,
                'password' => $request->password
            ];
            

        //     if (auth()->guard('admins')->attempt($regUser)) {
        //         $token = auth()->guard('admins')->createToken('authToken')->accessToken;
        //         return response()->json(['token' => $token], 200);
        //     } else {
        //         return response()->json(['error' => 'Unauthorised'], 401);
        //     }
        // }

        if (!auth()->guard('admins')->attempt($data)) {
            return response()->json(['error' => 'Unauthorised. Please try again'], 401);
        }

        $token = auth()->guard('admins')->user()->createToken('API Token')->accessToken;

        return response(['user' => auth()->guard('admins')->user(), 'token' => $token]);

    }

    
        protected function guard()
        {
            return Auth::guard('admins');
        }
}
