<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyPinjamRequest;
use App\Http\Requests\StorePinjamRequest;
use App\Http\Requests\UpdatePinjamRequest;
use App\Models\Kendaraan;
use App\Models\Pinjam;
use App\Models\LogPinjam;
use App\Models\Sopir;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;
use DB;
use Alert;

class PinjamController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('pinjam_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Pinjam::with(['kendaraan', 'borrowed_by', 'processed_by', 'sopir', 'created_by'])->select(sprintf('%s.*', (new Pinjam)->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                return view('partials.admintablesActions', compact('row'));
            });

            $table->editColumn('name', function ($row) {
                return $row->name ? ('<u>'.$row->name.'</u><br>No WhatsApp :<br>('.($row->no_wa).')') : '';
            });
            $table->editColumn('no_wa', function ($row) {
                return $row->no_wa ? $row->no_wa : '';
            });
            $table->addColumn('kendaraan_type', function ($row) {
                return $row->kendaraan ? ('<u>'.$row->kendaraan->no_pol.'</u><br>('.$row->kendaraan->type.')') : '';
            });
            $table->addColumn('waktu_peminjaman', function ($row) {
                return $row->date_start. '<br><i>sd</i><br>'. $row->date_end;
            });
            $table->editColumn('reason', function ($row) {
                return $row->reason ? $row->reason : '';
            });
            $table->editColumn('status', function ($row) {
                if ($row->status == 'diajukan') {
                    return '<span class="badge badge-primary">Diajukan Tanggal : '. $row->tanggal_pengajuan. '</span><br>
                        <span class="badge badge-primary">Surat Permohonan : <b>'. ($row->surat_permohonan ? 'Ada' : 'Belum Upload'). '</b></span><br>
                        <span class="badge badge-primary">Surat Ijin : <b>'. ($row->surat_izin ? 'Ada' : 'Belum Upload'). '</b></span>';
                } else if ($row->status == 'ditolak') {
                    $arr = explode(' : ', $row->status_text);
                    return '<span class="badge badge-dark">'. $arr[0].'<br>'. $arr[1] .'</span>';
                } else {
                    $status = '<span class="badge badge-'.Pinjam::STATUS_BACKGROUND[$row->status].'">'.$row->status_peminjaman.'</span>';
                    if ($row->status == 'diproses') {
                        $driver = '<br><span class="badge badge-warning">'.($row->sopir_id ? ('Sopir : '.$row->sopir->nama.'<br>No WA : ('.$row->sopir->no_wa.')') : 'Belum Pilih Sopir').'</span>';
                        return $status.' '.$driver;
                    }
                    return $status;
                }
            });
            $table->editColumn('surat_permohonan', function ($row) {
                $permohonan = $row->surat_permohonan ? '<a class="btn btn-xs btn-success" href="' . $row->surat_permohonan->getFullUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '<span class="badge badge-warning">Belum Upload</span>';
                $izin = $row->surat_izin ? '<a class="btn btn-xs btn-success" href="' . $row->surat_izin->getFullUrl() . '" target="_blank">' . trans('global.downloadFile') . '</a>' : '<span class="badge badge-warning">Belum Upload</span>';
                return 'Surat Permohonan :<br>'.$permohonan. '<br>Surat Izin Kegiatan : <br>'.$izin;
            });

            $table->rawColumns(['actions', 'name', 'placeholder', 'kendaraan_type', 'waktu_peminjaman', 'status', 'surat_permohonan']);

            return $table->make(true);
        }

        return view('admin.pinjams.index');
    }

    public function create()
    {
        abort_if(Gate::denies('pinjam_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $kendaraans = Kendaraan::pluck('type', 'id')->prepend(trans('global.pleaseSelect'), '');

        $sopirs = Sopir::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.pinjams.create', compact('kendaraans', 'sopirs'));
    }

    public function store(StorePinjamRequest $request)
    {
        $kendaraan = Kendaraan::find($request->kendaraan_id);

        $request->request->add(['status' => 'diajukan']);
        $request->request->add(['status_text' => 'Diajukan oleh "' . $request->name .' ('. $request->no_wa .')" peminjaman kendaraan "'.$kendaraan->nama .'"']);
        $request->request->add(['borrowed_by_id' => auth()->user()->id]);

        DB::beginTransaction();
        try {
            $pinjam = Pinjam::create($request->all());

            if ($request->input('surat_permohonan', false)) {
                $pinjam->addMedia(storage_path('tmp/uploads/' . basename($request->input('surat_permohonan'))))->toMediaCollection('surat_permohonan');
            }

            if ($request->input('surat_izin', false)) {
                $pinjam->addMedia(storage_path('tmp/uploads/' . basename($request->input('surat_izin'))))->toMediaCollection('surat_izin');
            }

            if ($media = $request->input('ck-media', false)) {
                Media::whereIn('id', $media)->update(['model_id' => $pinjam->id]);
            }

            LogPinjam::create([
                'peminjaman_id' => $pinjam->id,
                'kendaraan_id' => $pinjam->kendaraan_id,
                'peminjam_id' => $pinjam->borrowed_by_id,
                'jenis' => 'diajukan',
                'log' => 'Peminjaman kendaraan '. $pinjam->kendaraan->nama. ' Diajukan oleh "'. $pinjam->name.'" Untuk tanggal '. $pinjam->WaktuPeminjaman . ' Dengan keperluan "' . $pinjam->reason .'"',
            ]);

            DB::commit();

            Alert::success('Success', 'Peminjaman Kendaraan Berhasil Disimpan');

            return redirect()->route('admin.pinjams.index');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error-message', $e->getMessage())->withInput();
        }
    }

    public function edit(Pinjam $pinjam)
    {
        abort_if(Gate::denies('pinjam_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $kendaraans = Kendaraan::pluck('type', 'id')->prepend(trans('global.pleaseSelect'), '');

        $sopirs = Sopir::pluck('nama', 'id')->prepend(trans('global.pleaseSelect'), '');

        $pinjam->load('kendaraan', 'borrowed_by', 'processed_by', 'sopir', 'created_by');

        return view('admin.pinjams.edit', compact('kendaraans', 'pinjam', 'sopirs'));
    }

    public function update(UpdatePinjamRequest $request, Pinjam $pinjam)
    {
        $pinjam->update($request->all());

        if ($request->input('surat_permohonan', false)) {
            if (! $pinjam->surat_permohonan || $request->input('surat_permohonan') !== $pinjam->surat_permohonan->file_name) {
                if ($pinjam->surat_permohonan) {
                    $pinjam->surat_permohonan->delete();
                }
                $pinjam->addMedia(storage_path('tmp/uploads/' . basename($request->input('surat_permohonan'))))->toMediaCollection('surat_permohonan');
            }
        } elseif ($pinjam->surat_permohonan) {
            $pinjam->surat_permohonan->delete();
        }

        if ($request->input('surat_izin', false)) {
            if (! $pinjam->surat_izin || $request->input('surat_izin') !== $pinjam->surat_izin->file_name) {
                if ($pinjam->surat_izin) {
                    $pinjam->surat_izin->delete();
                }
                $pinjam->addMedia(storage_path('tmp/uploads/' . basename($request->input('surat_izin'))))->toMediaCollection('surat_izin');
            }
        } elseif ($pinjam->surat_izin) {
            $pinjam->surat_izin->delete();
        }

        if (count($pinjam->laporan_kegiatan) > 0) {
            foreach ($pinjam->laporan_kegiatan as $media) {
                if (! in_array($media->file_name, $request->input('laporan_kegiatan', []))) {
                    $media->delete();
                }
            }
        }
        $media = $pinjam->laporan_kegiatan->pluck('file_name')->toArray();
        foreach ($request->input('laporan_kegiatan', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $pinjam->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('laporan_kegiatan');
            }
        }

        if (count($pinjam->foto_kegiatan) > 0) {
            foreach ($pinjam->foto_kegiatan as $media) {
                if (! in_array($media->file_name, $request->input('foto_kegiatan', []))) {
                    $media->delete();
                }
            }
        }
        $media = $pinjam->foto_kegiatan->pluck('file_name')->toArray();
        foreach ($request->input('foto_kegiatan', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $pinjam->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('foto_kegiatan');
            }
        }

        return redirect()->route('admin.pinjams.index');
    }

    public function show(Pinjam $pinjam)
    {
        abort_if(Gate::denies('pinjam_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pinjam->load('kendaraan', 'borrowed_by', 'processed_by', 'sopir', 'created_by');

        return view('admin.pinjams.show', compact('pinjam'));
    }

    public function destroy(Pinjam $pinjam)
    {
        abort_if(Gate::denies('pinjam_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $pinjam->delete();

        return back();
    }

    public function massDestroy(MassDestroyPinjamRequest $request)
    {
        $pinjams = Pinjam::find(request('ids'));

        foreach ($pinjams as $pinjam) {
            $pinjam->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('pinjam_create') && Gate::denies('pinjam_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new Pinjam();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }

    public function accept(Request $request)
    {
        DB::beginTransaction();
        try {
            $data = Pinjam::find($request->id);
            $data->status = 'diproses';
            $data->status_text = 'Peminjaman kendaraan "'.$data->kendaraan->nama .'" Disetujui oleh "'. auth()->user()->name .'"';

            LogPinjam::create([
                'peminjaman_id' => $data->id,
                'kendaraan_id' => $data->kendaraan_id,
                'peminjam_id' => $data->borrowed_by_id,
                'jenis' => 'diproses',
                'log' => 'Peminjaman kendaraan '. $data->kendaraan->nama. ' Disetujui oleh "'. auth()->user()->name .'"',
            ]);

            $data->save();

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Pengajuan Peminjaman Berhasil Diterima']);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function reject(Request $request)
    {
        $id = $request->pinjam_id;
        $reason = $request->reason_rejection;

        DB::beginTransaction();
        try {
            $data = Pinjam::find($id);
            $data->status = 'ditolak';
            $data->status_text = 'Ditolak dengan alasan : "'. $reason .'"';

            LogPinjam::create([
                'peminjaman_id' => $data->id,
                'kendaraan_id' => $data->kendaraan_id,
                'peminjam_id' => $data->borrowed_by_id,
                'jenis' => 'ditolak',
                'log' => 'Peminjaman kendaraan '. $data->kendaraan->nama. ' untuk tanggal '. $data->WaktuPeminjaman . ' telah ditolak oleh "'. auth()->user()->name .'" dengan alasan : "'. $reason .'", Peminjaman telah Ditolak.',
            ]);

            $data->save();

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Pengajuan Peminjaman Berhasil Ditolak']);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function saveDriver(Request $request)
    {
        DB::beginTransaction();
        try {
            $sopir = Sopir::find($request->driver_id);

            $data = Pinjam::find($request->pinjam_id);
            $data->sopir_id = $sopir->id;
            $data->status_text = 'Peminjaman kendaraan "'.$data->kendaraan->nama .'" Diproses oleh "'. auth()->user()->name .'" Telah menugas kan "'. $sopir->nama. '" dengan No WA ('. $sopir->no_wa.') Sebagai Supir';

            LogPinjam::create([
                'peminjaman_id' => $data->id,
                'kendaraan_id' => $data->kendaraan_id,
                'peminjam_id' => $data->borrowed_by_id,
                'jenis' => 'diproses',
                'log' => 'Peminjaman kendaraan '. $data->kendaraan->nama. ' Diproses oleh "'. auth()->user()->name .'" Telah menugas kan "'. $sopir->nama. '" dengan No WA ('. $sopir->no_wa.') Sebagai Supir Untuk tanggal '. $data->WaktuPeminjaman . ' Dengan keperluan "' . $data->reason .'"',
            ]);

            $data->save();

            DB::commit();

            return response()->json(['status' => 'success', 'message' => 'Sopir Berhasil Dipilih']);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}
