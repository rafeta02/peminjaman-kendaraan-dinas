@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">

            <div class="card">
                <div class="card-header">
                    {{ trans('global.edit') }} {{ trans('cruds.logPinjam.title_singular') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("frontend.log-pinjams.update", [$logPinjam->id]) }}" enctype="multipart/form-data">
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <label for="peminjaman_id">{{ trans('cruds.logPinjam.fields.peminjaman') }}</label>
                            <select class="form-control select2" name="peminjaman_id" id="peminjaman_id">
                                @foreach($peminjamen as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('peminjaman_id') ? old('peminjaman_id') : $logPinjam->peminjaman->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('peminjaman'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('peminjaman') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.logPinjam.fields.peminjaman_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="kendaraan_id">{{ trans('cruds.logPinjam.fields.kendaraan') }}</label>
                            <select class="form-control select2" name="kendaraan_id" id="kendaraan_id">
                                @foreach($kendaraans as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('kendaraan_id') ? old('kendaraan_id') : $logPinjam->kendaraan->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('kendaraan'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('kendaraan') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.logPinjam.fields.kendaraan_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="peminjam_id">{{ trans('cruds.logPinjam.fields.peminjam') }}</label>
                            <select class="form-control select2" name="peminjam_id" id="peminjam_id">
                                @foreach($peminjams as $id => $entry)
                                    <option value="{{ $id }}" {{ (old('peminjam_id') ? old('peminjam_id') : $logPinjam->peminjam->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('peminjam'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('peminjam') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.logPinjam.fields.peminjam_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label>{{ trans('cruds.logPinjam.fields.jenis') }}</label>
                            <select class="form-control" name="jenis" id="jenis">
                                <option value disabled {{ old('jenis', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                                @foreach(App\Models\LogPinjam::JENIS_SELECT as $key => $label)
                                    <option value="{{ $key }}" {{ old('jenis', $logPinjam->jenis) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('jenis'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('jenis') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.logPinjam.fields.jenis_helper') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="log">{{ trans('cruds.logPinjam.fields.log') }}</label>
                            <textarea class="form-control" name="log" id="log">{{ old('log', $logPinjam->log) }}</textarea>
                            @if($errors->has('log'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('log') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.logPinjam.fields.log_helper') }}</span>
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