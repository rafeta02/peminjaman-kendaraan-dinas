@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    Upload Laporan Pertanggungjawaban
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.pinjams.uploadLaporan", [$pinjam->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf

                        <div class="row">
                            <div class="col-12">
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
                            </div>
                            <div class="col-12">
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
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 text-left">
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
