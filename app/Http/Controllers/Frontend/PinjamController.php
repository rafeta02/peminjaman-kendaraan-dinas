<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyPinjamRequest;
use App\Http\Requests\StorePinjamRequest;
use App\Http\Requests\UpdatePinjamRequest;
use App\Http\Requests\UpdateSuratPinjamRequest;
use App\Http\Requests\UpdateLaporanPinjamRequest;
use App\Http\Requests\BookPinjamRequest;
use App\Models\Kendaraan;
use App\Models\Pinjam;
use App\Models\LogPinjam;
use App\Models\Sopir;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;
use DB;
use Alert;

class PinjamController extends Controller
{
    use MediaUploadingTrait, CsvImportTrait;

    public function index()
    {
        $pinjams = Pinjam::with(['kendaraan', 'media'])->latest()->get();

        return view('frontend.pinjams.index', compact('pinjams'));
    }

    public function create(Request $request)
    {
        $kendaraan = Kendaraan::where('slug', $request->kendaraan)->first();

        if ($request->date) {
            session()->flashInput(['date_start' => Carbon::parse($request->date)->format('d-m-Y H:i')]);
        }

        return view('frontend.pinjams.create', compact('kendaraan'));
    }

    public function store(StorePinjamRequest $request)
    {
        $kendaraan = Kendaraan::find($request->kendaraan_id);

        $request->request->add(['status' => 'pinjam']);
        $request->request->add(['status_text' => 'Peminjaman Diajukan oleh "' . $request->name .' ('. $request->no_wa .')" Untuk kendaraan "'.$kendaraan->nama .'"']);
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
                'jenis' => 'pinjam',
                'log' => 'Peminjaman Kendaraan '. $pinjam->kendaraan->nama. ' Diajukan oleh "'. $pinjam->name.'" Untuk tanggal '. $pinjam->WaktuPeminjaman . ' Dengan keperluan "' . $pinjam->reason .'"',
            ]);

            DB::commit();

            Alert::success('Success', 'Peminjaman Kendaraan Berhasil Diajukan');

            return redirect()->route('frontend.pinjams.index');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error-message', $e->getMessage())->withInput();
        }

        return redirect()->route('frontend.pinjams.index');
    }

    public function book(Request $request)
    {
        $kendaraan = Kendaraan::where('slug', $request->kendaraan)->first();

        if ($request->date) {
            session()->flashInput(['date_start' => Carbon::parse($request->date)->format('d-m-Y H:i')]);
        }

        return view('frontend.pinjams.book', compact('kendaraan'));
    }

    public function storeBook(BookPinjamRequest $request)
    {
        $kendaraan = Kendaraan::find($request->kendaraan_id);

        $request->request->add(['status' => 'pesan']);
        $request->request->add(['status_text' => 'Pemesanan Peminjaman Diajukan Oleh "' . $request->name .' ('. $request->no_wa .')" Untuk Kendaraan "'.$kendaraan->nama .'"']);
        $request->request->add(['borrowed_by_id' => auth()->user()->id]);

        DB::beginTransaction();
        try {
            $pinjam = Pinjam::create($request->all());

            LogPinjam::create([
                'peminjaman_id' => $pinjam->id,
                'kendaraan_id' => $pinjam->kendaraan_id,
                'peminjam_id' => $pinjam->borrowed_by_id,
                'jenis' => 'pesan',
                'log' => 'Pemesanan Peminjaman Kendaraan '. $pinjam->kendaraan->nama. ' Diajukan oleh "'. $pinjam->name.'" Untuk tanggal '. $pinjam->WaktuPeminjaman . ' Dengan keperluan "' . $pinjam->reason .'"',
            ]);

            DB::commit();

            Alert::success('Success', 'Pemesanan Peminjaman Kendaraan Berhasil Diajukan');

            return redirect()->route('frontend.pinjams.index');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error-message', $e->getMessage())->withInput();
        }

        return redirect()->route('frontend.pinjams.index');
    }

    public function permohonan(Pinjam $pinjam)
    {

        $pinjam->load('kendaraan');

        return view('frontend.pinjams.permohonan', compact('pinjam'));
    }

    public function uploadPermohonan(UpdateSuratPinjamRequest $request, Pinjam $pinjam)
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

        Alert::success('Success', 'Surat Permohonan Peminjaman Kendaraan Berhasil Disimpan');

        return redirect()->route('frontend.pinjams.index');
    }

    public function edit(Pinjam $pinjam)
    {

        $pinjam->load('kendaraan');

        return view('frontend.pinjams.edit', compact('pinjam'));
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

        Alert::success('Success', 'Pengajuan Peminjaman Kendaraan Berhasil Disimpan');

        return redirect()->route('frontend.pinjams.index');
    }

    public function laporan(Pinjam $pinjam)
    {
        return view('frontend.pinjams.lpj', compact('pinjam'));
    }

    public function uploadLaporan(UpdateLaporanPinjamRequest $request, Pinjam $pinjam)
    {
        $pinjam->update([
            'laporan_kegiatan' => $request->laporan_kegiatan,
            'foto_kegiatan' => $request->fotos_kegiatan
        ]);

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

        Alert::success('Success', 'Laporan Pertanggungjawaban Berhasil Disimpan');

        return redirect()->route('frontend.pinjams.index');
    }

    public function show(Pinjam $pinjam)
    {
        $pinjam->load('kendaraan', 'borrowed_by', 'processed_by', 'sopir', 'created_by');

        return view('frontend.pinjams.show', compact('pinjam'));
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

    public function getKendaraan(Request $request)
    {
        $keywords = $request->input('keywords');
        $kendaraans = Kendaraan::where(function($q) use ($keywords) {
                    $q->where('plat_no', 'LIKE', "%{$keywords}%")
                    ->orWhere('type', 'LIKE', "%{$keywords}%");
                })
                ->orderBy('plat_no', 'ASC')
                ->get();

        foreach ($kendaraans as $kendaraan) {
            $formattedProducts[] = [
                'id' => $kendaraan->id,
                'text' => $kendaraan->nama,
                'deskripsi' => $kendaraan->description,
            ];
        }

        return response()->json($formattedProducts);
    }
}
