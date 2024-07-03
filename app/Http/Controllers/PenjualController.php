<?php

namespace App\Http\Controllers;

use App\Models\Kantin;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\Ruko;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenjualController extends Controller
{
    public function index()
    {
        $user = User::all();
        $ruko = Ruko::all();
        $menu = Menu::all();
        $transaksi = Transaksi::all();
        $pesanan = Pesanan::orderBy('id', 'DESC')->get();

        $data = Ruko::where('id_user', Auth::user()->id)->first();
        $kantins = Kantin::all();

        if ($data->id_kantin == '') {
            return view('penjual.lengkapi', compact('data', 'kantins'));
        } else {
            return view('penjual.index', compact('user', 'ruko', 'menu', 'transaksi', 'pesanan'));
        }
    }

    public function transaksi()
    {
        $user = User::all();
        $ruko = Ruko::all();
        $pesanan = Pesanan::groupBy('id_transaksi')->orderBy('id_transaksi', 'desc')->get();
        $data = Transaksi::orderBy('id', 'DESC')->get();

        return view('penjual.transaksi.datatransaksi', compact('data', 'user', 'ruko', 'pesanan'));
    }

    public function update_penjual(Request $request)
    {
        $validatedData = $request->validate(
            [
                'name' => 'required',
                'no_telpon' => 'required',
                'kantin' => 'required',
            ]
        );

        $id = Auth::user()->id;

        Ruko::where('id_user', $id)->update([
            'id_kantin' => $request->kantin,
            'nama_ruko' => $request->name,
            'no_telpon' => $request->no_telpon,
        ]);

        return redirect('/penjual')->with('success', 'Data Berhasil Diubah!!');
    }
}
