<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Pinjaman;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function uploadProduct(Request $request) {

        $validateData = Validator::make($request->all(),[
            'code_barang' => 'required',
            'nama_product' => 'required',
            'harga_jual' => 'required',
            'stock_masuk' => 'required'
        ]);

        if ($validateData->fails()) {
            return response()->json([
                'status' => -1,
                'message' => 'data tidak boleh kosong',
                'product' => $validateData->errors()
            ],401);
        }

        $product = Product::where('code_barang', $request->code_barang)->first();

        if ($product) {
            return response()->json([
                'status' => 0,
                'message' => 'kode barang sudah terdaftar',
            ],401);
        } else {
            $fotoProduct = $request->foto_product; 
            $fotoProduct = str_replace('data:image/png;base64,', '', $fotoProduct);
            $fotoProduct = str_replace(' ', '+', $fotoProduct);
            $suratx = md5(uniqid(rand(), true));
            $nameFotoProduct = $suratx.'.'.'jpg';

            Storage::disk('product')->put($nameFotoProduct, base64_decode($fotoProduct));

            $product = Product::create([
                'code_barang' => $request->input('code_barang'),
                'nama_product' => $request->input('nama_product'),
                'harga_jual' => $request->input('harga_jual'),
                'stock_masuk' => $request->input('stock_masuk'),
            ]);

            if ($product) {
                return response()->json([
                    'status' => 1,
                    'message' => 'Success to Upload Product',
                    'product' => $product
                ],200);
            }else{
                return response()->json([
                    'status' => 0,
                    'message' => 'Failed to Upload Product'
                ],401);
            }
        }
    }

    public function getStockMasukMonth($bulan, $tahun){

        $stockBulan = Product::whereMonth('created_at',$$bulan)
                        ->whereYear('created_at', $tahun)
                        ->sum('stock_masuk');

        return response()->json([
            'status' => 1,
            'message' => 'Success',
            'stock_masuk' => $stockBulan
        ],200);

    }

    public function getStockMasukYear($tahun){

        $currentYear = date('Y');

        $stockTahun = Product::whereYear('created_at', $tahun)
                        ->sum('stock_masuk');

       return response()->json([
            'status' => 1,
            'message' => 'Success',
            'stock_masuk' => $stockTahun
        ],200);

    }

    public function getOutStockMonth($bulan, $tahun){

        $currentMonth = date('m');
        $currentYear = date('Y');

        $stockBulan = Pinjaman::whereMonth('created_at',$bulan)
                        ->whereYear('created_at', $tahun)
                        ->sum('jumlah_pinjam');

        return response()->json([
            'status' => 1,
            'message' => 'Success',
            'stock_masuk' => $stockBulan
        ],200);

    }

    public function getOutStockYear($tahun){

        $currentYear = date('Y');

        $stockYear = Pinjaman::whereYear('created_at', $tahun)
                        ->sum('jumlah_pinjam');

       return response()->json([
            'status' => 1,
            'message' => 'Success',
            'stock_masuk' => $stockYear
        ],200);

    }

    public function getAllProduct() {
        $product = Product::all();

        return response()->json([
            'status' => 1,
            'message' => 'Success',
            'product' => $product
        ],200);
        
    }
}
