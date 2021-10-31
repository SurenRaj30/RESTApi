<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request){
        
        $request->validate([
            'name' => 'required|max:10',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        $token = $user->createToken('authToken')->accessToken;

        return response()->json(['token'=>$token, 'message'=>'New user created succesfully'], 200);
       
    }

        //Login user

        public function login(Request $request){
            
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:8',
            ]);

            $regUser = [
                'email' => $request->email,
                'password' => $request->password
            ];

            if (auth()->attempt($regUser)) {
                $token = auth()->user()->createToken('VimigoAuthApp')->accessToken;
                return response()->json(['token' => $token], 200);
            } else {
                return response()->json(['error' => 'Unauthorised'], 401);
            }
        }
}
