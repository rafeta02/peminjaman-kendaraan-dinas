@extends('layouts.frontend')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 mb-5">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-3 col-sm-6">
                            <div class="card-box bg-blue">
                                <div class="inner">
                                    <h3> {{ $mobil }} </h3>
                                    <p>Jumlah Mobil</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-car" aria-hidden="true"></i>
                                </div>
                                <a href="{{ route('frontend.kendaraans.index') }}" class="card-box-footer">View More <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
