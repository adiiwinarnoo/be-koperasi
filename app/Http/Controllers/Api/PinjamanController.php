<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ModelPinjaman;
use App\Models\ModelProduct;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PinjamanController extends Controller
{
    public function pengajuanPinjaman(Request $request) {

        $currentMonth = date('m');
        $currentYear = date('Y');

        $validateData = Validator::make($request->all(),[
            'id_product' => 'required',
            'nik_karyawan' => 'required',
            'nama_karyawan' => 'required',
            'jumlah_pinjam' => 'required',
            'harga' => 'required',
        ]);

        if ($validateData->fails()) {
            return response()->json([
                'status' => -1,
                'message' => 'data tidak boleh kosong',
                'product' => $validateData->errors()
            ],401);
        }

        $product = ModelProduct::where('id', $request->id_product)->first();

        $hargaProduct = ModelPinjaman::where('nik_karyawan', $request->nik_karyawan)
                        ->where('nama_karyawan', $request->nama_karyawan)
                        ->whereMonth('created_at',$currentMonth)
                        ->whereYear('created_at', $currentYear)
                        ->sum('harga');

        $outStockProduct = ModelPinjaman::where('id_product', $request->id_product)
                        ->sum('jumlah_pinjam');
        
        $stockProduct = ModelProduct::where('id', $request->id_product)
                        ->pluck('stock_masuk')
                        ->first();

        $hargaProduct += $request->harga;
        $outStockProduct += $request->jumlah_pinjam;

        if (!$product) {
            return response()->json([
                'status' => 0,
                'message' => 'Product tidak ada',
            ],404);
        }

        if($hargaProduct > 500000){
            return response()->json([
                'status' => 0,
                'message' => 'Gagal, Tagihan anda Bulan ini lebih dari 500.000',
            ],400);
        }else{
            if($outStockProduct > $stockProduct){
                return response()->json([
                    'status' => 0,
                    'message' => 'Gagal, Stock tidak Sesuai',
                ],400);
            }else{
                $pinjaman = ModelPinjaman::create([
                    'id_product' => $request->input('id_product'),
                    'nik_karyawan' => $request->input('nik_karyawan'),
                    'nama_karyawan' => $request->input('nama_karyawan'),
                    'jumlah_pinjam' => $request->input('jumlah_pinjam'),
                    'harga' => $request->input('harga'),
                ]);

                $pinjaman->nama_product = $product->nama_product;
        
                return response()->json([
                    'status' => 1,
                    'message' => 'Success',
                    'dataPinjaman' => $pinjaman
                ],200);
            }
       
        }

    }

    public function getAllPinjaman($tanggal) {
        $product = ModelPinjaman::whereDate("created_at",$tanggal)->get();

        return response()->json([
            'status' => 1,
            'message' => 'Success',
            'pinjaman' => $product
        ],200);
    }
}
