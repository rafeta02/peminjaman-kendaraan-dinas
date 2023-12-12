<a href="{{ route('admin.pinjams.show', $row->id) }}" class="btn btn-sm btn-block mb-1 btn-primary" >View</a>

@if ($row->status == 'pesan')
    <button class="btn btn-sm btn-block mb-1 btn-success button-accept-booking" data-id="{{ $row->id }}">Setujui</button>
    <button class="btn btn-sm btn-block mb-1 btn-danger button-reject" data-id="{{ $row->id }}">Tolak</button>
@elseif ($row->status == 'pinjam')
    <button class="btn btn-sm btn-block mb-1 btn-success button-accept-pinjam" data-id="{{ $row->id }}">Setujui</button>
    <button class="btn btn-sm btn-block mb-1 btn-danger button-reject" data-id="{{ $row->id }}">Tolak</button>
@elseif ($row->status == 'disetujui')
    @if (!$row->sopir_id)
        <button class="btn btn-sm btn-block mb-1 btn-warning button-driver" data-id="{{ $row->id }}">Pilih Sopir</button>
    @else
        <button class="btn btn-sm btn-block mb-1 btn-warning button-driver" data-id="{{ $row->id }}">Ubah Sopir</button>
    @endif
    @if (!$row->surat_balasan)
        <a href="{{ route('admin.pinjams.balasan', $row->id) }}" class="btn btn-sm btn-block mb-1 btn-warning" >Surat Balasan</a>
    @endif
    <button class="btn btn-sm btn-block mb-1 btn-danger button-reject" data-id="{{ $row->id }}">Batalkan</button>
@endif

