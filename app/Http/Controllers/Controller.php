<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function login(Request $request){
        if (!empty($request)){
            //get user in data base
            $user = User::where('email', $request->email,'password')->first();
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])){
                //$user = Auth::user();
                $token=$user->createToken($user->name)->plainTextToken;
                return response()->json(['user'=>$user,
                   'status' =>true,'token'=>$token]);
            }else{
                return response()->json([
                   'status' =>false]);
            }
        }
        return response()->json(["success"=>false],510);
    }
}
