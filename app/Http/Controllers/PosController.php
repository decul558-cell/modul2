<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Tampil halaman POS
    public function index()
    {
        return view('js.pos');
    }

    // Cari barang by kode (AJAX)
    public function cariBarang(Request $req)
    {
        $barang = Barang::find($req->kode);

        if (!$barang) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Barang tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $barang
        ]);
    }

    // Simpan transaksi (AJAX)
    public function bayar(Request $req)
    {
        $items = $req->items;
        $total = $req->total;

        DB::beginTransaction();
        try {
            $id_penjualan = DB::table('penjualan')->insertGetId([
                'tgl_penjualan' => now(),
                'total'         => $total,
            ], 'id_penjualan');

            foreach ($items as $item) {
                DB::table('penjualan_detail')->insert([
                    'id_penjualan' => $id_penjualan,
                    'id_barang'    => $item['kode'],
                    'jumlah'       => $item['jumlah'],
                    'subtotal'     => $item['subtotal'],
                ]);
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'message' => 'Transaksi berhasil disimpan',
                'data'    => ['id_penjualan' => $id_penjualan]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => 'Transaksi gagal: ' . $e->getMessage()
            ], 500);
        }
    }
}
