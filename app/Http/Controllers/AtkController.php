<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Gunakan DB facade

class AtkController extends Controller
{
    public function index()
    {
        // Ambil data dari view_atk menggunakan query mentah
        $atkData = DB::select('SELECT * FROM view_atk');

        return view('atk.index', ['atkData' => $atkData]);
    }
}