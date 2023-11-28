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
                        <div class="form-group">
                            <label for="name">{{ trans('cruds.pinjam.fields.name') }}</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{ old('name', $pinjam->name) }}">
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.pinjam.fields.name_helper') }}</span>
                        </div>
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
                        <div class="form-group">
                            <label class="required" for="kendaraan_id">{{ trans('cruds.pinjam.fields.kendaraan') }}</label>
                            <select class="form-control select2" name="kendaraan_id" id="kendaraan_id" required>
                                @foreach($kendaraans as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('kendaraan_id') ? old('kendaraan_id') : $pinjam->kendaraan->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('kendaraan'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('kendaraan') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.pinjam.fields.kendaraan_helper') }}</span>
                        </div>
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
                        <div class="form-group">
                            <label>{{ trans('cruds.pinjam.fields.status') }}</label>
                            <select class="form-control" name="status" id="status">
                                <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\Pinjam::STATUS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('status', $pinjam->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('status'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('status') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.pinjam.fields.status_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="sopir_id">{{ trans('cruds.pinjam.fields.sopir') }}</label>
                            <select class="form-control select2" name="sopir_id" id="sopir_id">
                                @foreach($sopirs as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('sopir_id') ? old('sopir_id') : $pinjam->sopir->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('sopir'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('sopir') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.pinjam.fields.sopir_helper') }}</span>
                        </div>
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
                        <div class="form-group">
                            <label for="laporan_kegiatan">{{ trans('cruds.pinjam.fields.laporan_kegiatan') }}</label>
                            <div class="needsclick dropzone" id="laporan_kegiatan-dropzone">
                            </div>
                            @if($errors->has('laporan_kegiatan'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('laporan_kegiatan') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.pinjam.fields.laporan_kegiatan_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="foto_kegiatan">{{ trans('cruds.pinjam.fields.foto_kegiatan') }}</label>
                            <div class="needsclick dropzone" id="foto_kegiatan-dropzone">
                            </div>
                            @if($errors->has('foto_kegiatan'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('foto_kegiatan') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.pinjam.fields.foto_kegiatan_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ trans('global.save') }}
                            </button>
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
    var uploadedLaporanKegiatanMap = {}
Dropzone.options.laporanKegiatanDropzone = {
    url: '{{ route('frontend.pinjams.storeMedia') }}',
    maxFilesize: 5, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 5
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="laporan_kegiatan[]" value="' + response.name + '">')
      uploadedLaporanKegiatanMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedLaporanKegiatanMap[file.name]
      }
      $('form').find('input[name="laporan_kegiatan[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($pinjam) && $pinjam->laporan_kegiatan)
          var files =
            {!! json_encode($pinjam->laporan_kegiatan) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="laporan_kegiatan[]" value="' + file.file_name + '">')
            }
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
    var uploadedFotoKegiatanMap = {}
Dropzone.options.fotoKegiatanDropzone = {
    url: '{{ route('frontend.pinjams.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="foto_kegiatan[]" value="' + response.name + '">')
      uploadedFotoKegiatanMap[file.name] = response.name
    },
    removedfile: function (file) {
      console.log(file)
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedFotoKegiatanMap[file.name]
      }
      $('form').find('input[name="foto_kegiatan[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($pinjam) && $pinjam->foto_kegiatan)
      var files = {!! json_encode($pinjam->foto_kegiatan) !!}
          for (var i in files) {
          var file = files[i]
          this.options.addedfile.call(this, file)
          this.options.thumbnail.call(this, file, file.preview ?? file.preview_url)
          file.previewElement.classList.add('dz-complete')
          $('form').append('<input type="hidden" name="foto_kegiatan[]" value="' + file.file_name + '">')
        }
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
@endsection