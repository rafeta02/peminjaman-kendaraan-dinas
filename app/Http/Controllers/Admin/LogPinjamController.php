<?php

namespace App\Http\Controllers\Admin;

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
use Yajra\DataTables\Facades\DataTables;

class LogPinjamController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('log_pinjam_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = LogPinjam::with(['peminjaman', 'kendaraan', 'peminjam'])->select(sprintf('%s.*', (new LogPinjam)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate      = 'log_pinjam_show';
                $editGate      = 'log_pinjam_edit';
                $deleteGate    = 'log_pinjam_delete';
                $crudRoutePart = 'log-pinjams';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                ));
            });

            $table->addColumn('peminjaman_reason', function ($row) {
                return $row->peminjaman ? $row->peminjaman->reason : '';
            });

            $table->addColumn('kendaraan_type', function ($row) {
                return $row->kendaraan ? $row->kendaraan->type : '';
            });

            $table->addColumn('peminjam_name', function ($row) {
                return $row->peminjam ? $row->peminjam->name : '';
            });

            $table->editColumn('jenis', function ($row) {
                return $row->jenis ? LogPinjam::JENIS_SELECT[$row->jenis] : '';
            });
            $table->editColumn('log', function ($row) {
                return $row->log ? $row->log : '';
            });

            $table->rawColumns(['actions', 'placeholder', 'peminjaman', 'kendaraan', 'peminjam']);

            return $table->make(true);
        }

        return view('admin.logPinjams.index');
    }

    public function create()
    {
        abort_if(Gate::denies('log_pinjam_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $peminjamen = Pinjam::pluck('reason', 'id')->prepend(trans('global.pleaseSelect'), '');

        $kendaraans = Kendaraan::pluck('type', 'id')->prepend(trans('global.pleaseSelect'), '');

        $peminjams = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.logPinjams.create', compact('kendaraans', 'peminjamen', 'peminjams'));
    }

    public function store(StoreLogPinjamRequest $request)
    {
        $logPinjam = LogPinjam::create($request->all());

        return redirect()->route('admin.log-pinjams.index');
    }

    public function edit(LogPinjam $logPinjam)
    {
        abort_if(Gate::denies('log_pinjam_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $peminjamen = Pinjam::pluck('reason', 'id')->prepend(trans('global.pleaseSelect'), '');

        $kendaraans = Kendaraan::pluck('type', 'id')->prepend(trans('global.pleaseSelect'), '');

        $peminjams = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $logPinjam->load('peminjaman', 'kendaraan', 'peminjam');

        return view('admin.logPinjams.edit', compact('kendaraans', 'logPinjam', 'peminjamen', 'peminjams'));
    }

    public function update(UpdateLogPinjamRequest $request, LogPinjam $logPinjam)
    {
        $logPinjam->update($request->all());

        return redirect()->route('admin.log-pinjams.index');
    }

    public function show(LogPinjam $logPinjam)
    {
        abort_if(Gate::denies('log_pinjam_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logPinjam->load('peminjaman', 'kendaraan', 'peminjam');

        return view('admin.logPinjams.show', compact('logPinjam'));
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
