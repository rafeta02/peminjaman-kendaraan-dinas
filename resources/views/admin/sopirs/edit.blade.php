@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.sopir.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.sopirs.update", [$sopir->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="nip">{{ trans('cruds.sopir.fields.nip') }}</label>
                <input class="form-control {{ $errors->has('nip') ? 'is-invalid' : '' }}" type="text" name="nip" id="nip" value="{{ old('nip', $sopir->nip) }}" required>
                @if($errors->has('nip'))
                    <span class="text-danger">{{ $errors->first('nip') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sopir.fields.nip_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="nama">{{ trans('cruds.sopir.fields.nama') }}</label>
                <input class="form-control {{ $errors->has('nama') ? 'is-invalid' : '' }}" type="text" name="nama" id="nama" value="{{ old('nama', $sopir->nama) }}" required>
                @if($errors->has('nama'))
                    <span class="text-danger">{{ $errors->first('nama') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sopir.fields.nama_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="no_wa">{{ trans('cruds.sopir.fields.no_wa') }}</label>
                <input class="form-control {{ $errors->has('no_wa') ? 'is-invalid' : '' }}" type="text" name="no_wa" id="no_wa" value="{{ old('no_wa', $sopir->no_wa) }}">
                @if($errors->has('no_wa'))
                    <span class="text-danger">{{ $errors->first('no_wa') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.sopir.fields.no_wa_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection