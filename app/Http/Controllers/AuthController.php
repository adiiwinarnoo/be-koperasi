<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Kasir;

class AuthController extends Controller
{
    public function login(Request $request) {
        
        $validator = Validator::make($request->all(), [
            'nomor_induk' => 'required',
            'password' => 'required',
        ]);

        if($validator->fails()) {
            return response()->json([
                'status' => 0,
                'user' => $validator->errors()
            ], 401);
        }

        $user = Kasir::where('nomor_induk', $request->nomor_induk)->first();
        if(!$user) {
            return response()->json([
                'status' => 0,
                'message' => 'Nomor Induk Tidak Terdaftar'
            ], 401);
        }
        
        if($request->only('nomor_indukk','password')){
            return response()->json([
                'status' => 1,
                'message' => "Login Berhasil",
                'dataLogin' => $user
            ], 200);
        }else{
            return response()->json([
                'status' => 0,
                'message' => "Password Tidak Sesuai"                                     
            ], 401);
        }
    }
}
