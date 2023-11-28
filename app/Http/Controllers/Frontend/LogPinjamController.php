<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyLogPinjamRequest;
use App\Http\Requests\StoreLogPinjamRequest;
use App\Http\Requests\UpdateLogPinjamRequest;
use App\Models\Kendaraan;
use App\Models\LogPinjam;
use App\Models\Pinjam;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LogPinjamController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('log_pinjam_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logPinjams = LogPinjam::with(['peminjaman', 'kendaraan', 'peminjam'])->get();

        return view('frontend.logPinjams.index', compact('logPinjams'));
    }

    public function create()
    {
        abort_if(Gate::denies('log_pinjam_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $peminjamen = Pinjam::pluck('reason', 'id')->prepend(trans('global.pleaseSelect'), '');

        $kendaraans = Kendaraan::pluck('type', 'id')->prepend(trans('global.pleaseSelect'), '');

        $peminjams = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('frontend.logPinjams.create', compact('kendaraans', 'peminjamen', 'peminjams'));
    }

    public function store(StoreLogPinjamRequest $request)
    {
        $logPinjam = LogPinjam::create($request->all());

        return redirect()->route('frontend.log-pinjams.index');
    }

    public function edit(LogPinjam $logPinjam)
    {
        abort_if(Gate::denies('log_pinjam_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $peminjamen = Pinjam::pluck('reason', 'id')->prepend(trans('global.pleaseSelect'), '');

        $kendaraans = Kendaraan::pluck('type', 'id')->prepend(trans('global.pleaseSelect'), '');

        $peminjams = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $logPinjam->load('peminjaman', 'kendaraan', 'peminjam');

        return view('frontend.logPinjams.edit', compact('kendaraans', 'logPinjam', 'peminjamen', 'peminjams'));
    }

    public function update(UpdateLogPinjamRequest $request, LogPinjam $logPinjam)
    {
        $logPinjam->update($request->all());

        return redirect()->route('frontend.log-pinjams.index');
    }

    public function show(LogPinjam $logPinjam)
    {
        abort_if(Gate::denies('log_pinjam_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logPinjam->load('peminjaman', 'kendaraan', 'peminjam');

        return view('frontend.logPinjams.show', compact('logPinjam'));
    }

    public function destroy(LogPinjam $logPinjam)
    {
        abort_if(Gate::denies('log_pinjam_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logPinjam->delete();

        return back();
    }

    public function massDestroy(MassDestroyLogPinjamRequest $request)
    {
        $logPinjams = LogPinjam::find(request('ids'));

        foreach ($logPinjams as $logPinjam) {
            $logPinjam->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
