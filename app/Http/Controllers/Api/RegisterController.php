<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ModelKasir;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register (Request $request) {

        $validateData = Validator::make($request->all(),[
            'nomor_induk' => 'required',
            'nama_admin' => 'required',
            'jabatan' => 'required',
            'password' => 'required'
        ]);

        if ($validateData->fails()) {
            return response()->json([
                'status' => -1,
                'message' => 'data tidak boleh kosong',
                'user' => $validateData->errors()
            ],401);
        }

        $user = ModelKasir::where('nomor_induk', $request->nomor_induk)->first();

        if ($user) {
            return response()->json([
                'status' => 0,
                'message' => 'nomor induk sudah terdaftar',
            ],401);
        } else {
            $user = ModelKasir::create([
                'nomor_induk' => $request->input('nomor_induk'),
                'nama_admin' => $request->input('nama_admin'),
                'jabatan' => $request->input('jabatan'),
                'password' => Hash::make($request->input('password'))
            ]);

            if ($user) {
                return response()->json([
                    'status' => 1,
                    'message' => 'Success to register',
                    'user' => $user
                ],200);
            }else{
                return response()->json([
                    'status' => 0,
                    'message' => 'Failed to register'
                ],401);
            }
        }
    }
}
