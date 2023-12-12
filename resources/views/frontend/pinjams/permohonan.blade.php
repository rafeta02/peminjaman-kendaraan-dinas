@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    Upload Surat Permohonan
                </div>

                <div class="card-body">
                    @if (session()->has('error-message'))
                        <p class="text-danger">
                            {{session()->get('error-message')}}
                        </p>
                    @endif

                    <form method="POST" action="{{ route("frontend.pinjams.uploadPermohonan", [$pinjam->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="row">
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="required" for="surat_permohonan">{{ trans('cruds.pinjam.fields.surat_permohonan') }} <small>(PDF/Word)</small></label>
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
                            <div class="col-md-6 col-sm-12">
                                <div class="form-group">
                                    <label class="required" for="surat_izin">{{ trans('cruds.pinjam.fields.surat_izin') }} <small>(PDF/Word)</small></label>
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
    url: '{{ route('frontend.pinjams.storeMedia') }}',
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
@endsection
