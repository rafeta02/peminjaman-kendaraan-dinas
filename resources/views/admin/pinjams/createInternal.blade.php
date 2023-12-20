@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.pinjam.title_singular') }} Internal
    </div>

    <div class="card-body">
        @if (session()->has('error-message'))
            <p class="text-danger">
                {{session()->get('error-message')}}
            </p>
        @endif

        <form method="POST" action="{{ route("admin.pinjams.storeInternal") }}" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label class="required" for="kendaraan_id">{{ trans('cruds.pinjam.fields.kendaraan') }}</label>
                        <select name="kendaraan_id" id="kendaraan_id" class="form-control select2 {{ $errors->has('kendaraan') ? 'is-invalid' : '' }}" style="width: 100%;" required>
                            <option></option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label class="required" for="date_start">{{ trans('cruds.pinjam.fields.date_start') }}</label>
                        <input class="form-control datetime {{ $errors->has('date_start') ? 'is-invalid' : '' }}" type="text" name="date_start" id="date_start" value="{{ old('date_start') }}" required>
                        @if($errors->has('date_start'))
                            <span class="text-danger">{{ $errors->first('date_start') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.pinjam.fields.date_start_helper') }}</span>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label class="required" for="date_end">{{ trans('cruds.pinjam.fields.date_end') }}</label>
                        <input class="form-control datetime {{ $errors->has('date_end') ? 'is-invalid' : '' }}" type="text" name="date_end" id="date_end" value="{{ old('date_end') }}" required>
                        @if($errors->has('date_end'))
                            <span class="text-danger">{{ $errors->first('date_end') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.pinjam.fields.date_end_helper') }}</span>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="required" for="reason">{{ trans('cruds.pinjam.fields.reason') }}</label>
                        <input class="form-control {{ $errors->has('reason') ? 'is-invalid' : '' }}" type="text" name="reason" id="reason" value="{{ old('reason', '') }}" required>
                        @if($errors->has('reason'))
                            <span class="text-danger">{{ $errors->first('reason') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.pinjam.fields.reason_helper') }}</span>
                    </div>
                </div>
            </div>
            <div class="row mt-5">
                <div class="col-12 text-center">
                    <div class="form-group">
                        <button class="btn btn-danger" type="submit">
                            {{ trans('global.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>



@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#kendaraan_id').select2({
            templateResult: formatProduct,
            templateSelection: formatProductSelection,
            ajax: {
                    url: "{{ route('admin.kendaraans.getKendaraan') }}",
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            keywords: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
        });

        function formatProduct(kendaraan) {
            if (!kendaraan.id) {
                return kendaraan.text;
            }

            var kendaraanInfo = $('<span>' + kendaraan.text + '</span><br><small class="stock-info">' + kendaraan.deskripsi + '</small>');
            return kendaraanInfo;
        }

        function formatProductSelection(kendaraan) {
            return kendaraan.text;
        }

        $("#date_start").datetimepicker({
            minDate: 'dateToday',
            onSelect: function(date) {
                $("#date_end").datetimepicker('option', 'minDate', date);
            }
        });

        $("#date_end").datetimepicker();
    });
</script>

<script>
    $(function () {
        $('#date_start').datetimepicker().on('dp.change', function (e) {
            $('#date_end').data('DateTimePicker').minDate(e.date);
        });

        $('#date_end').datetimepicker().on('dp.change', function (e) {
            $('#date_start').data('DateTimePicker').maxDate(e.date);
        });
    });
</script>
@endsection
