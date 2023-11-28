@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.logPinjam.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.log-pinjams.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.logPinjam.fields.peminjaman') }}
                        </th>
                        <td>
                            {{ $logPinjam->peminjaman->reason ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.logPinjam.fields.kendaraan') }}
                        </th>
                        <td>
                            {{ $logPinjam->kendaraan->type ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.logPinjam.fields.peminjam') }}
                        </th>
                        <td>
                            {{ $logPinjam->peminjam->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.logPinjam.fields.jenis') }}
                        </th>
                        <td>
                            {{ App\Models\LogPinjam::JENIS_SELECT[$logPinjam->jenis] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.logPinjam.fields.log') }}
                        </th>
                        <td>
                            {{ $logPinjam->log }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.log-pinjams.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection