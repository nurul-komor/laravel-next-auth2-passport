<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required",
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8',
        ]);
        if ($validator->fails()) {
            return $validator->errors();
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $token = $user->createToken("auth_token")->accessToken;
        return response()->json([
            "status" => "success",
            "message" => "User created successfully",
            "data" => $user,
            "token" => $token
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return $validator->errors();
        }
        $user = auth()->attempt($request->only('email', 'password'));

        $user = Auth::user();
        if ($user != "") {
            $message = $user->createToken('auth_token')->accessToken;
            $status = 1;
            $resCode = 200;
        } else {
            $status = 0;
            $message = "user not found";
            $resCode = 401;
        }


        return response()->json([
            "status" => $status,
            'message' => $message,

        ], $resCode);
    }
    public function show($id)
    {
        $user = User::find($id);
        return $user;
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $validator = Validator::make($request->all(), [
        //     "name" => "required",
        //     'email' => 'required|email|unique:users',
        //     'password' => 'required|confirmed|min:8',
        // ]);
        // if ($validator->fails()) {
        //     return $validator->errors();
        // }
        // $user = DB::table("user")->update([
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        // ]);
        // $token = auth()->user()->createToken("auth_token")->accessToken;
        // return response()->json([
        //     "status" => "success",
        //     "message" => "User updated successfully",
        //     "data" => $user,
        //     "token" => $token
        // ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user != "") {
            return response()->json([
                'message' => "user deleted successfully",
            ]);
        } else {
            return response()->json([
                'error' => "user not found"
            ], 404);
        }
    }
}