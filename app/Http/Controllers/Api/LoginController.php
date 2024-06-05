<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\ModelKasir;

class LoginController extends Controller
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
            ], 400);
        }

        $user = ModelKasir::where('nomor_induk', $request->nomor_induk)->first();
        if(!$user) {
            return response()->json([
                'status' => 0,
                'message' => 'Nomor Induk Tidak Terdaftar',
                'dataLogin' => null
            ], 401);
        }
        
        if (Auth::guard('kasir')->attempt($request->only('nomor_induk', 'password'))) {
            return response()->json([
                'status' => 1,
                'message' => "Login Berhasil",
                'dataLogin' => $user
            ], 200);
        } else {
            return response()->json([
                'status' => 0,
                'message' => "Password Tidak Sesuai",
                'dataLogin' => null
            ], 401);
        }
    }
}
