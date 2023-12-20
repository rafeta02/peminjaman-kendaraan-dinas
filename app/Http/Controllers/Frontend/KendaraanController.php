<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Kendaraan;
use App\Models\Pinjam;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class KendaraanController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->type ?? null;
        $query = Kendaraan::with(['media']);

        if ($request->type) {
            $query->where('type', 'like', '%' . $request->type . '%')
            ->orWhere('description', 'like', '%' . $request->type . '%');
        }

        $kendaraans = $query->paginate(9);

        return view('frontend.kendaraans.index', compact('kendaraans', 'type'));
    }

    public function calender(Request $request)
    {
        if (!$request->kendaraan) {
            return redirect()->back();
        }

        $events = [];
        $kendaraan = Kendaraan::where('slug', $request->kendaraan)->first();
        $pinjams = Pinjam::with('kendaraan')->whereHas('kendaraan', function ($q) use ($kendaraan)  {
            $q->where('id', $kendaraan->id);
        })->whereIn('status_calender', ['booked', 'borrowed', 'noted'])->get();

        foreach($pinjams as $pinjam) {
            $text = $pinjam->status_calender == 'borrowed' ? 'Dipinjam' : 'Dibooking';

            $events[] = [
                'title' => 'Sudah '. $text. ' Oleh : '. $pinjam->name,
                'start' => Carbon::parse($pinjam->date_start)->format('Y-m-d H:i:s'),
                'end' => Carbon::parse($pinjam->date_end)->format('Y-m-d H:i:s'),
                'header' => $pinjam->status_calender == 'borrowed' ? 'Informasi Peminjaman' : 'Informasi Pemesanan',
                'body' => 'Kendaraan Dinas : "'.$pinjam->kendaraan->nama . '" Telah '. $text. ' Oleh : "'. $pinjam->name .'" Pada Tanggal : "'. $pinjam->waktu_peminjaman. '"',
                'color' => $pinjam->status_calender == 'borrowed' ?  '#ADD8E6' : '#C8EACB',
                // 'url' => route('admin.process.show', $pinjam->id)
            ];
        }

        return view('frontend.kendaraans.calender', compact('events', 'kendaraan'));
    }
}
