<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $barangs = DB::table('barang')->orderByDesc('tgl_input')->get();
        return view('barang.index', compact('barangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'required|string|max:50',
            'harga' => 'required|integer|min:1',
        ]);

        DB::insert('INSERT INTO barang (id_barang, nama, harga) VALUES (?, ?, ?)', [
            '', $request->nama, $request->harga
        ]);

        return redirect()->route('barang.index')
                         ->with('success', 'Barang berhasil ditambahkan!');
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama'  => 'required|string|max:50',
            'harga' => 'required|integer|min:1',
        ]);

        DB::table('barang')
            ->where('id_barang', $id)
            ->update([
                'nama'  => $request->nama,
                'harga' => $request->harga,
            ]);

        return redirect()->route('barang.index')
                         ->with('success', 'Barang berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        DB::table('barang')->where('id_barang', $id)->delete();

        return redirect()->route('barang.index')
                         ->with('success', 'Barang berhasil dihapus!');
    }

    public function cetakPdf(Request $request)
    {
        $request->validate([
            'ids'    => 'required|string',
            'coordX' => 'required|integer|min:1|max:5',
            'coordY' => 'required|integer|min:1|max:8',
        ]);

        $ids = collect(explode(',', $request->ids))
            ->map(fn($v) => trim($v))
            ->filter(fn($v) => preg_match('/^[a-zA-Z0-9]+$/', $v))
            ->values();

        $barangs = DB::table('barang')
            ->whereIn('id_barang', $ids)
            ->get()
            ->keyBy('id_barang');

        $ordered = $ids->map(fn($id) => $barangs->get($id))->filter()->values();

        $coordX    = (int) $request->coordX;
        $coordY    = (int) $request->coordY;
        $startSlot = ($coordY - 1) * 5 + ($coordX - 1);

        $pdf = Pdf::loadView('barang.pdf', [
            'barangs'   => $ordered,
            'startSlot' => $startSlot,
            'totalSlot' => 40,
            'cols'      => 5,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('tag_harga_' . now()->format('YmdHis') . '.pdf');
    }
}
