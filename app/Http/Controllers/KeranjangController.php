<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Keranjang;
use Illuminate\Support\Facades\DB;
use App\Models\Produk;
class KeranjangController extends Controller
{
public function store(Request $request)
{
DB::transaction(function () use ($request) {

$produk = Produk::lockForUpdate()->findOrFail($request->produk_id);

// cek stok cukup
if ($produk->stok < $request->jumlah) {
    abort(400, 'Stok tidak mencukupi');
    }

    // simpan ke keranjang
    Keranjang::create([
    'user_id' => Auth::id(),
    'produk_id' => $request->produk_id,
    'jumlah' => $request->jumlah,
    ]);

    // kurangi stok
    $produk->decrement('stok', $request->jumlah);
    });

    return redirect('/admin/keranjangs');
    }

}
