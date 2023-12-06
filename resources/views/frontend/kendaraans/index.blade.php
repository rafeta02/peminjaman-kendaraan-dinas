@extends('layouts.frontend')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    {{ trans('global.list') }} {{ trans('cruds.kendaraan.title_singular') }} 
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route("frontend.kendaraans.index") }}" enctype="multipart/form-data">
                        <div class="row mb-5">
                            <div class="col row">
                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <label class="required" for="type">{{ trans('cruds.kendaraan.fields.type') }}</label>
                                        <input class="form-control" type="text" name="type" id="type" value="{{ old('type', $type) }}" required>
                                        @if($errors->has('type'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('type') }}
                                            </div>
                                        @endif
                                        <span class="help-block">{{ trans('cruds.kendaraan.fields.type_helper') }}</span>
                                    </div>
                                </div>
                            </div>
            
                            <div class="col-auto align-self-end">
                                <button type="submit" class="btn btn-primary">Cari</button>
                                <a href="{{ route('frontend.kendaraans.index') }}" class="btn btn-danger">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($kendaraans as $key => $kendaraan)
                            <div class="col-lg-4 col-md-6 col-sm-12">
                                <div class="card mb-3">
                                    <div id="carouselExample{{ $key }}" class="carousel slide carousel-fade" data-ride="carousel">
                                        <div class="carousel-inner">
                                            {{-- @if(count($kendaraan->gallery) > 0)
                                                @foreach ($kendaraan->gallery as $media)
                                                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                                        <img class="d-block w-100" src="{{ $media->getFullUrl() }}">
                                                    </div>
                                                @endforeach
                                            @else --}}
                                            <div class="carousel-item active">
                                                <img class="d-block w-100" src="{{ asset('img/empty-room.jpg') }}">
                                            </div>
                                            {{-- @endif --}}
                                        </div>
                                        <a class="carousel-control-prev" href="#carouselExample{{ $key }}" role="button" data-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="carousel-control-next" href="#carouselExample{{ $key }}" role="button" data-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                    <div class="card-body">
                                        <h5 class="card-title">{{ $kendaraan->nama ?? '' }}</h5>
                                        <p class="card-text">{{ $kendaraan->description ?? '' }}</p>
                                    </div>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item text-center"><b>Kapasitas : {{ $kendaraan->capacity ?? '' }} Orang</b></li>
                                        <li class="list-group-item">
                                            <a class="btn btn-md btn-block btn-success" href="{{ route('frontend.kendaraans.calender', ['kendaraan' => $kendaraan->slug]) }}">
                                                Cek Jadwal
                                            </a>
                                            <a class="btn btn-md btn-block btn-warning" href="{{ route('frontend.pinjams.create', ['kendaraan' => $kendaraan->slug]) }}">
                                                Ajukan Peminjaman
                                            </a>
                                        </li>
                                        <li class="list-group-item">

                                        </li>
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex mt-3">
            {!! $kendaraans->links() !!}
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
</script>
@endsection
