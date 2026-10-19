<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Pegawai;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPegawai = Pegawai::count();
        $totalDivisi = Divisi::count();

        $tanggalHariIni = now()
            ->locale('id')
            ->translatedFormat('l, d F Y');

        $bulanIni = now()
            ->locale('id')
            ->translatedFormat('F Y');

        return view('dashboard', compact(
            'totalPegawai',
            'totalDivisi',
            'tanggalHariIni',
            'bulanIni'
        ));
    }
}
