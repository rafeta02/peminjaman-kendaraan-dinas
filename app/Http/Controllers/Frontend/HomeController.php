<?php

namespace App\Http\Controllers\Frontend;
use App\Models\Kendaraan;

class HomeController
{
    public function index()
    {
        $mobil = Kendaraan::all()->count();

        return view('frontend.home', compact('mobil'));
    }
}
