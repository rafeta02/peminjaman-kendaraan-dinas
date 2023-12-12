@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div style="margin-bottom: 10px;" class="row">
                <div class="col-lg-12">
                    <a class="btn btn-primary" href="{{ route('frontend.pinjams.book') }}">
                        {{ trans('global.add') }} Pemesanan
                    </a>
                    <a class="btn btn-success" href="{{ route('frontend.pinjams.create') }}">
                        {{ trans('global.add') }} {{ trans('cruds.pinjam.title_singular') }}
                    </a>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    {{ trans('global.list') }} {{ trans('cruds.pinjam.title_singular') }}
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class=" table table-bordered table-striped table-hover datatable datatable-Pinjam">
                            <thead>
                                <tr>
                                    <th class="text-center">
                                        {{ trans('cruds.pinjam.fields.name') }}
                                    </th>
                                    <th class="text-center">
                                        {{ trans('cruds.pinjam.fields.kendaraan') }}
                                    </th>
                                    <th class="text-center">
                                        Waktu Peminjaman
                                    </th>
                                    <th class="text-center">
                                        {{ trans('cruds.pinjam.fields.status') }}
                                    </th>
                                    <th class="text-center">
                                        {{ trans('cruds.pinjam.fields.surat_permohonan') }}
                                    </th>
                                    <th class="text-center">
                                        &nbsp;
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pinjams as $key => $pinjam)
                                    <tr data-entry-id="{{ $pinjam->id }}">
                                        <td class="text-center">
                                            <u>{{ $pinjam->name ?? '' }}</u><br>No WhatsApp :<br>({{ $pinjam->no_wa ?? '' }})
                                        </td>
                                        <td class="text-center">
                                            <u>{{ $pinjam->kendaraan->no_pol ?? '' }}</u><br>({{ $pinjam->kendaraan->type ?? '' }})
                                        </td>
                                        <td class="text-center">
                                            {{ $pinjam->date_start }}<br><i>sd</i><br>{{ $pinjam->date_end }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-{{ App\Models\Pinjam::STATUS_BACKGROUND[$pinjam->status] }}">{{ App\Models\Pinjam::STATUS_SELECT[$pinjam->status] }}</span>
                                            <br>
                                            @if($pinjam->status == 'ditolak')
                                                {{ $pinjam->status_text }}
                                            @endif
                                            @php
                                                $now = Carbon\Carbon::now();
                                                $end = Carbon\Carbon::parse($pinjam->date_end);
                                            @endphp
                                            @if($pinjam->status == 'disetujui' && $now->gt($end))
                                                <br>
                                                @if(count($pinjam->laporan_kegiatan) > 0)
                                                    Laporan Kegiatan :<br><span class="badge badge-success">Sudah Upload</span>
                                                @else
                                                    Laporan Kegiatan :<br><span class="badge badge-warning">Belum Upload</span>
                                                @endif
                                                <br>
                                                @if(count($pinjam->foto_kegiatan) > 0)
                                                    Foto Kegiatan :<br><span class="badge badge-success">Sudah Upload</span>
                                                @else
                                                    Foto Kegiatan :<br><span class="badge badge-warning">Belum Upload</span>
                                                @endif
                                            @endif
                                            @if($pinjam->status == 'disetujui' && $pinjam->sopir_id)
                                                <span class="badge badge-warning">Sopir : <u>{{ $pinjam->sopir->nama }}</u><br>No WA : ({{ $pinjam->sopir->no_wa }})</span><br>
                                            @endif
                                            @if($pinjam->status == 'disetujui' && $pinjam->surat_balasan)
                                                Surat Balasan :<br><a class="btn btn-xs btn-success" href="{{ $pinjam->surat_izin->getFullUrl() }}" target="_blank">{{ trans('global.downloadFile') }} </a>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            @if($pinjam->surat_permohonan)
                                                Surat Permohonan :<br><span class="badge badge-success">Sudah Upload</span>
                                            @else
                                                Surat Permohonan :<br><span class="badge badge-warning">Belum Upload</span>
                                            @endif
                                            <br>
                                            @if($pinjam->surat_izin)
                                                Surat Izin :<br><span class="badge badge-success">Sudah Upload</span>
                                            @else
                                                Surat Izin :<br><span class="badge badge-warning">Belum Upload</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a class="btn btn-sm btn-primary btn-block" href="{{ route('frontend.pinjams.show', $pinjam->id) }}">
                                                {{ trans('global.view') }}
                                            </a>
                                            @if($pinjam->status == 'pesan' || $pinjam->status == 'pinjam')
                                                <a class="btn btn-sm btn-info btn-block mb-2" href="{{ route('frontend.pinjams.edit', $pinjam->id) }}">
                                                    {{ trans('global.edit') }}
                                                </a>
                                                <form action="{{ route('frontend.pinjams.destroy', $pinjam->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="btn btn-sm btn-danger btn-block" value="{{ trans('global.delete') }}">
                                                </form>
                                            @endif
                                            @if($pinjam->status == 'terpesan')
                                                <a class="btn btn-sm btn-danger btn-block" href="{{ route('frontend.pinjams.permohonan', $pinjam->id) }}">
                                                    Upload Surat
                                                </a>
                                            @endif
                                            @if($pinjam->status == 'disetujui')
                                                <a class="btn btn-sm btn-warning btn-block" href="{{ route('frontend.pinjams.laporan', $pinjam->id) }}">
                                                    Upload LPJ
                                                </a>
                                            @endif
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
$(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    pageLength: 25,
  });

  let table = $('.datatable-Pinjam:not(.ajaxTable)').DataTable({ buttons: dtButtons });

  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
})
</script>
@endsection
