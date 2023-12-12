@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.pinjam.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.pinjams.update", [$pinjam->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="name">{{ trans('cruds.pinjam.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $pinjam->name) }}">
                        @if($errors->has('name'))
                            <span class="text-danger">{{ $errors->first('name') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.pinjam.fields.name_helper') }}</span>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label class="required" for="no_wa">{{ trans('cruds.pinjam.fields.no_wa') }}</label>
                        <input class="form-control {{ $errors->has('no_wa') ? 'is-invalid' : '' }}" type="text" name="no_wa" id="no_wa" value="{{ old('no_wa', $pinjam->no_wa) }}" required>
                        @if($errors->has('no_wa'))
                            <span class="text-danger">{{ $errors->first('no_wa') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.pinjam.fields.no_wa_helper') }}</span>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="required" for="kendaraan_id">{{ trans('cruds.pinjam.fields.kendaraan') }}</label>
                        <select class="form-control select2 {{ $errors->has('kendaraan') ? 'is-invalid' : '' }}" name="kendaraan_id" id="kendaraan_id" required>
                            @foreach($kendaraans as $id => $entry)
                                <option value="{{ $id }}" {{ (old('kendaraan_id') ? old('kendaraan_id') : $pinjam->kendaraan->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('kendaraan'))
                            <span class="text-danger">{{ $errors->first('kendaraan') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.pinjam.fields.kendaraan_helper') }}</span>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label class="required" for="date_start">{{ trans('cruds.pinjam.fields.date_start') }}</label>
                        <input class="form-control datetime {{ $errors->has('date_start') ? 'is-invalid' : '' }}" type="text" name="date_start" id="date_start" value="{{ old('date_start', $pinjam->date_start) }}" required>
                        @if($errors->has('date_start'))
                            <span class="text-danger">{{ $errors->first('date_start') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.pinjam.fields.date_start_helper') }}</span>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label class="required" for="date_end">{{ trans('cruds.pinjam.fields.date_end') }}</label>
                        <input class="form-control datetime {{ $errors->has('date_end') ? 'is-invalid' : '' }}" type="text" name="date_end" id="date_end" value="{{ old('date_end', $pinjam->date_end) }}" required>
                        @if($errors->has('date_end'))
                            <span class="text-danger">{{ $errors->first('date_end') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.pinjam.fields.date_end_helper') }}</span>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="required" for="reason">{{ trans('cruds.pinjam.fields.reason') }}</label>
                        <input class="form-control {{ $errors->has('reason') ? 'is-invalid' : '' }}" type="text" name="reason" id="reason" value="{{ old('reason', $pinjam->reason) }}" required>
                        @if($errors->has('reason'))
                            <span class="text-danger">{{ $errors->first('reason') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.pinjam.fields.reason_helper') }}</span>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="surat_permohonan">{{ trans('cruds.pinjam.fields.surat_permohonan') }} <small>(PDF/Word)</small></label>
                        <div class="needsclick dropzone {{ $errors->has('surat_permohonan') ? 'is-invalid' : '' }}" id="surat_permohonan-dropzone">
                        </div>
                        @if($errors->has('surat_permohonan'))
                            <span class="text-danger">{{ $errors->first('surat_permohonan') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.pinjam.fields.surat_permohonan_helper') }}</span>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="form-group">
                        <label for="surat_izin">{{ trans('cruds.pinjam.fields.surat_izin') }} <small>(PDF/Word)</small></label>
                        <div class="needsclick dropzone {{ $errors->has('surat_izin') ? 'is-invalid' : '' }}" id="surat_izin-dropzone">
                        </div>
                        @if($errors->has('surat_izin'))
                            <span class="text-danger">{{ $errors->first('surat_izin') }}</span>
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



@endsection

@section('scripts')
<script>
    Dropzone.options.suratPermohonanDropzone = {
    url: '{{ route('admin.pinjams.storeMedia') }}',
    maxFilesize: 5, // MB
    maxFiles: 1,
    acceptedFiles: "application/pdf,.doc,.docx",
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
    url: '{{ route('admin.pinjams.storeMedia') }}',
    maxFilesize: 5, // MB
    maxFiles: 1,
    acceptedFiles: "application/pdf,.doc,.docx",
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
    url: '{{ route('admin.pinjams.storeMedia') }}',
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
    url: '{{ route('admin.pinjams.storeMedia') }}',
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
