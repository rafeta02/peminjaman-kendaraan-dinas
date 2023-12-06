@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.pinjam.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.pinjams.update", [$pinjam->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="required" for="name">{{ trans('cruds.pinjam.fields.name') }}</label>
                                    <input class="form-control" type="text" name="name" id="name" value="{{ old('name', $pinjam->name) }}">
                                    @if($errors->has('name'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('name') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.pinjam.fields.name_helper') }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="required" for="no_wa">{{ trans('cruds.pinjam.fields.no_wa') }}</label>
                                    <input class="form-control" type="text" name="no_wa" id="no_wa" value="{{ old('no_wa', $pinjam->no_wa) }}" required>
                                    @if($errors->has('no_wa'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('no_wa') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.pinjam.fields.no_wa_helper') }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="required" for="kendaraan_id">{{ trans('cruds.pinjam.fields.kendaraan') }}</label>
                                    <select class="form-control select2" name="kendaraan_id" id="kendaraan_id" required>
                                        <option value="{{ $pinjam->kendaraan_id }}" selected="selected">{{$pinjam->kendaraan->nama}}</option>
                                    </select>
                                    @if($errors->has('kendaraan'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('kendaraan') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.pinjam.fields.kendaraan_helper') }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="required" for="date_start">{{ trans('cruds.pinjam.fields.date_start') }}</label>
                                    <input class="form-control datetime" type="text" name="date_start" id="date_start" value="{{ old('date_start', $pinjam->date_start) }}" required>
                                    @if($errors->has('date_start'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('date_start') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.pinjam.fields.date_start_helper') }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="required" for="date_end">{{ trans('cruds.pinjam.fields.date_end') }}</label>
                                    <input class="form-control datetime" type="text" name="date_end" id="date_end" value="{{ old('date_end', $pinjam->date_end) }}" required>
                                    @if($errors->has('date_end'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('date_end') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.pinjam.fields.date_end_helper') }}</span>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="required" for="reason">{{ trans('cruds.pinjam.fields.reason') }}</label>
                                    <input class="form-control" type="text" name="reason" id="reason" value="{{ old('reason', $pinjam->reason) }}" required>
                                    @if($errors->has('reason'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('reason') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.pinjam.fields.reason_helper') }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="surat_permohonan">{{ trans('cruds.pinjam.fields.surat_permohonan') }}</label>
                                    <div class="needsclick dropzone" id="surat_permohonan-dropzone">
                                    </div>
                                    @if($errors->has('surat_permohonan'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('surat_permohonan') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.pinjam.fields.surat_permohonan_helper') }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="surat_izin">{{ trans('cruds.pinjam.fields.surat_izin') }}</label>
                                    <div class="needsclick dropzone" id="surat_izin-dropzone">
                                    </div>
                                    @if($errors->has('surat_izin'))
                                        <div class="invalid-feedback">
                                            {{ $errors->first('surat_izin') }}
                                        </div>
                                    @endif
                                    <span class="help-block">{{ trans('cruds.pinjam.fields.surat_izin_helper') }}</span>
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

        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    Dropzone.options.suratPermohonanDropzone = {
    url: '{{ route('frontend.pinjams.storeMedia') }}',
    maxFilesize: 5, // MB
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 5
    },
    success: function (file, response) {
      $('form').find('input[name="surat_permohonan"]').remove()
      $('form').append('<input type="hidden" name="surat_permohonan" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="surat_permohonan"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($pinjam) && $pinjam->surat_permohonan)
      var file = {!! json_encode($pinjam->surat_permohonan) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="surat_permohonan" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
<script>
    Dropzone.options.suratIzinDropzone = {
    url: '{{ route('frontend.pinjams.storeMedia') }}',
    maxFilesize: 5, // MB
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 5
    },
    success: function (file, response) {
      $('form').find('input[name="surat_izin"]').remove()
      $('form').append('<input type="hidden" name="surat_izin" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="surat_izin"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($pinjam) && $pinjam->surat_izin)
      var file = {!! json_encode($pinjam->surat_izin) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="surat_izin" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}
</script>
<script>
    $(document).ready(function() {
        $('#kendaraan_id').select2({
            templateResult: formatProduct,
            templateSelection: formatProductSelection,
            ajax: {
                    url: "{{ route('frontend.pinjams.getKendaraan') }}",
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
    });
</script>

<script>
    $(function () {
        $('#date_start').datetimepicker().on('dp.change', function (e) {
            $('#date_end').data('DateTimePicker').minDate(e.date);
        });

        $$('#date_end').datetimepicker().on('dp.change', function (e) {
            $('#date_start').data('DateTimePicker').maxDate(e.date);
            $('#date_end').data('DateTimePicker').minDate($('#date_start').val());
        });
    });
</script>
@endsection
