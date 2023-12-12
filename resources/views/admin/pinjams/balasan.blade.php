@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Upload Surat Balasan
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.pinjams.storeBalasan", [$pinjam->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="surat_balasan">{{ trans('cruds.pinjam.fields.surat_balasan') }} <small>(PDF/Word)</small></label>
                        <div class="needsclick dropzone {{ $errors->has('surat_balasan') ? 'is-invalid' : '' }}" id="surat_balasan-dropzone">
                        </div>
                        @if($errors->has('surat_balasan'))
                            <span class="text-danger">{{ $errors->first('surat_balasan') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.pinjam.fields.surat_balasan_helper') }}</span>
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



@endsection

@section('scripts')
<script>
    Dropzone.options.suratBalasanDropzone = {
    url: '{{ route('admin.pinjams.storeMedia') }}',
    maxFilesize: 2, // MB
    maxFiles: 1,
    acceptedFiles: "application/pdf,.doc,.docx",
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2
    },
    success: function (file, response) {
      $('form').find('input[name="surat_balasan"]').remove()
      $('form').append('<input type="hidden" name="surat_balasan" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="surat_balasan"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($pinjam) && $pinjam->surat_balasan)
      var file = {!! json_encode($pinjam->surat_balasan) !!}
          this.options.addedfile.call(this, file)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="surat_balasan" value="' + file.file_name + '">')
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
