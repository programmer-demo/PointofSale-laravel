@extends('layouts.master')

@section('title')
{{--Income Report {{ tanggal_indonesia($tanggalAwal, false) }} -- {{ tanggal_indonesia($tanggalAkhir, false) }}--}}
Income Report {{ tanggal_indonesia($start_date, false) }} -- {{ tanggal_indonesia($end_date, false) }}
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('/AdminLTE-2/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endpush

@section('breadcrumb')
    @parent
    <li class="active">Report</li>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <button onclick="updatePeriode()" class="btn btn-primary btn-flat"><i class="fa fa-plus-circle"></i> Change Date</button>
{{--                <!-- <a href="{{ route('laporan.export_pdf', [$tanggalAwal, $tanggalAkhir]) }}" target="_blank" class="btn btn-success btn-flat"><i class="fa fa-file-excel-o"></i> Export PDF</a> -->--}}
                 <a href="{{ route('report.export_pdf', [$start_date, $end_date]) }}" target="_blank" class="btn btn-success btn-flat"><i class="fa fa-file-excel-o"></i> Export PDF</a>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-stiped table-bordered table-hover">
                    <thead>
                        <th width="5%">#</th>
                        <th>Date</th>
                        <th>Sale</th>
                        <th>Purchase</th>
                        <th>Expenses</th>
                        <th>Income</th>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- visit "codeastro" for more projects! -->
@includeIf('report.form')
@endsection

@push('scripts')
<script src="{{ asset('/AdminLTE-2/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script>
    let table;

    $(function () {
        table = $('.table').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            autoWidth: false,
            ajax: {
                {{--url: '{{ route('laporan.data', [$tanggalAwal, $tanggalAkhir]) }}',--}}
                url: '{{ route('report.data', [$start_date, $end_date]) }}',
            },
            columns: [
                {data: 'DT_RowIndex', searchable: false, sortable: false},
                // {data: 'tanggal'},
                {data: 'date'},
                // {data: 'penjualan'},
                {data: 'sale'},
                // {data: 'pembelian'},
                {data: 'purchase'},
                // {data: 'pengeluaran'},
                {data: 'production'},
                // {data: 'pendapatan'}
                {data: 'income'}
            ],
            dom: 'Brt',
            bSort: false,
            bPaginate: false,
        });

        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });
    });

    function updatePeriode() {
        $('#modal-form').modal('show');
    }
</script>
@endpush
