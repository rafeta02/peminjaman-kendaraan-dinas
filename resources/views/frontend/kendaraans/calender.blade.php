@extends('layouts.frontend')
@section('styles')
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' />
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header">
                    Kalender Peminjaman <strong>"{{ $kendaraan->nama }}"</strong>
                </div>

                <div class="card-body">
                    <div id='calendar'></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalInformasi" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalInformasiTitle">Informasi Peminjaman</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p id="modalInformasiBody">Body</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
<script src='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.js'></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script>
    $(document).ready(function () {
        var kendaraan = {!! json_encode($kendaraan) !!};
        // page is now ready, initialize the calendar...
        events={!! json_encode($events) !!};
        // $('#calendar').fullCalendar({
        //     // put your options and callbacks here
        //     events: events,
        //     defaultView: 'listWeek',
        //     timeFormat: 'H(:mm)'
        // })
        $('#calendar').fullCalendar({
            // put your options and callbacks here
            events: events,
            // eventColor: '#991f00',
            header: {
                left: 'month, agendaWeek',
                center: 'title',
                right: 'prev,next today',
            },
            timeFormat: 'H(:mm)',
            allDaySlot: false,
            eventClick: function(calEvent, jsEvent, view) {
                $('#modalInformasiTitle').text(calEvent.header);
                $('#modalInformasiBody').text(calEvent.body);
                $('#modalInformasi').modal();
            },
            dayClick: function(date, jsEvent, view) {
                var today = moment().format('YYYY-MM-DD');

                if(moment(date.format()).isSameOrAfter(today, 'day'))
                {
                    swal({
                        title: 'Apakah Anda Ingin Mengajukan Peminjaman Kendaraan ?',
                        text: 'Pengajuan peminjaman kendaraan pada tanggal ' + date.format(),
                        icon: 'warning',
                        // buttons: ["Cancel", "Yes!"],
                        buttons: {
                            cancel: "Cancel",
                            book: {
                                text: "Pesan",
                                value: 'pesan',
                                className: "btn-primary"
                            },
                            pinjam: {
                                text: "Pinjam",
                                value: 'pinjam',
                                className: "btn-success"
                            }
                        },
                        showSpinner: true
                    }).then(function(value) {
                        switch (value) {
                            case "pesan":

                                window.location.href = "{{ route('frontend.pinjams.book') }}?kendaraan=" + kendaraan.slug + "&date=" + date.format();

                            break;

                            case "pinjam":

                                window.location.href = "{{ route('frontend.pinjams.create') }}?kendaraan=" + kendaraan.slug + "&date=" + date.format();
                            break;
                        }
                    });
                }
            }
        })
});
</script>
@stop
