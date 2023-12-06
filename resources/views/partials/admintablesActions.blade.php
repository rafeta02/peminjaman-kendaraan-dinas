<a href="{{ route('admin.pinjams.show', $row->id) }}" class="btn btn-sm btn-block mb-1 btn-primary" >View</a>

@if ($row->status == 'diajukan')
    <button class="btn btn-sm btn-block mb-1 btn-success button-accept" data-id="{{ $row->id }}">Setujui</button>
    <button class="btn btn-sm btn-block mb-1 btn-danger button-reject" data-id="{{ $row->id }}">Tolak</button>
@elseif ($row->status == 'diproses')
    @if (!$row->sopir_id)
        <button class="btn btn-sm btn-block mb-1 btn-warning button-driver" data-id="{{ $row->id }}">Pilih Sopir</button>
    @else
        <button class="btn btn-sm btn-block mb-1 btn-warning button-driver" data-id="{{ $row->id }}">Ubah Sopir</button>
    @endif
    <button class="btn btn-sm btn-block mb-1 btn-danger button-reject" data-id="{{ $row->id }}">Batalkan</button>
@endif

