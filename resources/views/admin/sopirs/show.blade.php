@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.sopir.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.sopirs.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.sopir.fields.nip') }}
                        </th>
                        <td>
                            {{ $sopir->nip }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.sopir.fields.nama') }}
                        </th>
                        <td>
                            {{ $sopir->nama }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.sopir.fields.no_wa') }}
                        </th>
                        <td>
                            {{ $sopir->no_wa }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.sopirs.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection