@extends('layouts.admin')
@section('content')
@can('pinjam_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.pinjams.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.pinjam.title_singular') }}
            </a>
            {{-- <button class="btn btn-warning" data-toggle="modal" data-target="#csvImportModal">
                {{ trans('global.app_csvImport') }}
            </button>
            @include('csvImport.modal', ['model' => 'Pinjam', 'route' => 'admin.pinjams.parseCsvImport']) --}}
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.pinjam.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <table class=" table table-bordered table-striped table-hover ajaxTable datatable datatable-Pinjam">
            <thead>
                <tr>
                    <th width="10">

                    </th>
                    <th>
                        {{ trans('cruds.pinjam.fields.name') }}
                    </th>
                    <th>
                        {{ trans('cruds.pinjam.fields.kendaraan') }}
                    </th>
                    <th>
                        Waktu Peminjaman
                    </th>
                    <th>
                        {{ trans('cruds.pinjam.fields.reason') }}
                    </th>
                    <th>
                        {{ trans('cruds.pinjam.fields.surat_permohonan') }}
                    </th>
                    <th>
                        {{ trans('cruds.pinjam.fields.status') }}
                    </th>
                    <th>
                        &nbsp;
                    </th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade" id="driverModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Pilih Sopir</h4>
            </div>
            <div class="modal-body">
                <form id="driverForm" class="form-horizontal">
                    <input type="hidden" name="pinjam_id" id="driver_pinjam_id">
                    <div class="form-group">
                        <label for="driver" class="col-sm-2 control-label">Sopir</label>
                        <div class="col-sm-12">
                            <select class="form-control select2" name="driver_id" id="driver_id" style="width: 100%;">
                                <option></option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10 mt-4">
                        <button type="submit" class="btn btn-warning" id="driverButton" value="save">Pilih</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="rejectionModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Alasan Penolakan atau Pembatalan</h4>
            </div>
            <div class="modal-body">
                <form id="rejectionForm" class="form-horizontal">
                   <input type="hidden" name="pinjam_id" id="rejection_pinjam_id">
                    <div class="form-group">
                        <label for="driver" class="col-sm-2 control-label">Alasan</label>
                        <div class="col-sm-12">
                            <textarea class="form-control ckeditor" name="reason_rejection" id="reason_rejection"></textarea>
                        </div>
                    </div>
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-danger" id="rejectionButton">Tolak</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection
@section('scripts')
@parent
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
$(function () {
    let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

    let dtOverrideGlobals = {
        buttons: dtButtons,
        processing: true,
        serverSide: true,
        retrieve: true,
        aaSorting: [],
        ajax: "{{ route('admin.pinjams.index') }}",
        columns: [
            { data: 'placeholder', name: 'placeholder' },
            { data: 'name', name: 'name', class: 'text-center' },
            { data: 'kendaraan_type', name: 'kendaraan.type', class: 'text-center' },
            { data: 'waktu_peminjaman', name: 'waktu_peminjaman', class: 'text-center' },
            { data: 'reason', name: 'reason', class: 'text-justify' },
            { data: 'surat_permohonan', name: 'surat_permohonan', sortable: false, searchable: false, class: 'text-center' },
            { data: 'status', name: 'status', class: 'text-center' },
            { data: 'actions', name: '{{ trans('global.actions') }}' }
        ],
        orderCellsTop: true,
        // order: [[ 4, 'desc' ]],
        pageLength: 25,
    };
    let table = $('.datatable-Pinjam').DataTable(dtOverrideGlobals);
    $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
        $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('body').on('click', '.button-accept', function () {
        event.preventDefault();
        const id = $(this).data('id');
        swal({
            title: 'Apakah pengajuan akan diterima ?',
            text: 'Pengajuan peminjaman kendaraan akan diterima',
            icon: 'warning',
            buttons: ["Cancel", "Yes!"],
            showSpinner: true
        }).then(function(value) {
            if (value) {
                showLoading();
                $.ajax({
                    type: "POST",
                    url: "{{ route('admin.pinjams.accept') }}",
                    data: {
                        id: id
                    },
                    success: function (response) {
                        hideLoading();
                        if (response.status == 'success') {
                            swal("Success", response.message, "success");
                            table.ajax.reload();
                        } else {
                            swal("Warning!", response.message, 'error');
                        }
                    }
                });
            }
        });
    });

    $('body').on('click', '.button-reject', function () {
        event.preventDefault();
        const id = $(this).data('id');
        const text = $(this).text();
        $('#rejection_pinjam_id').val(id);
        $('#rejectionButton').text(text);
        $('#rejectionModal').modal('show');
    });

    $('#rejectionButton').click(function (e) {
        e.preventDefault();
        if (!$.trim($("#reason_rejection").val())) {
            swal("Warning!", 'Alasan tidak boleh kosong', 'error');
            return;
        } else {
            swal({
            title: 'Apakah pengajuan akan ditolak atau dibatalkan ?',
            text: 'Pengajuan peminjaman kendaraan akan ditolak atau dibatalkan ',
            icon: 'warning',
            buttons: ["Cancel", "Yes!"],
            showSpinner: true
            }).then(function(value) {
                if (value) {
                    $('#rejectionModal').modal('hide');
                    showLoading();
                    $.ajax({
                        data: $('#rejectionForm').serialize(),
                        url: "{{ route('admin.pinjams.reject') }}",
                        type: "POST",
                        dataType: 'json',
                        success: function (response) {
                            $('#rejectionForm').trigger("reset");
                            hideLoading();
                            if (response.status == 'success') {
                                table.ajax.reload();
                                swal("Success", response.message, 'success');
                            } else {
                                swal("Warning!", response.message, 'error');
                            }
                        }
                    });
                }
            });
        }
    });

    $('body').on('click', '.button-driver', function () {
        event.preventDefault();
        const id = $(this).data('id');
        $('#driver_pinjam_id').val(id);
        $('#driverModal').modal('show');
    });

    $('#driverButton').click(function (e) {
        e.preventDefault();
        showLoading();
        $.ajax({
            data: $('#driverForm').serialize(),
            url: "{{ route('admin.pinjams.saveDriver') }}",
            type: "POST",
            dataType: 'json',
            success: function (response) {
                $('#driverForm').trigger("reset");
                $('#driverModal').modal('hide');
                hideLoading();
                if (response.status == 'success') {
                    table.ajax.reload();
                    swal("Success", response.message, 'success');
                } else {
                    swal("Warning!", response.message, 'error');
                }
            }
        });
    });

    $('#driver_id').select2({
        ajax: {
            url: "{{ route('admin.sopirs.getSopir') }}",
            data: function(params) {
                return {
                    keywords: params.term
                };
            },
            dataType: 'json',
            processResults: function(data) {
                return {
                    results: data
                };
            },
            cache: true
        }
    });
});

</script>
@endsection
