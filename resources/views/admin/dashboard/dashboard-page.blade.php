@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')

    <h1>
        Dashboard
    </h1>
    <ol class="breadcrumb">
        <li class="active"><a href="#"><i class="fa fa-desktop"></i> Dashboard</a></li>

    </ol>

@stop

@section('content')
    <div class="row">
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3>{{ $usulan }}</h3>

                    <p>Sedang Diajukan</p>
                </div>
                <div class="icon">
                    <i class="fa fa-hourglass-half"></i>
                </div>
                <a href="{{ url('seleksi-usulan') }}" class="small-box-footer">Selengkapnya <i
                        class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-yellow">
                <div class="inner">
                    <h3>{{ $usulan_direvisi }}</h3>

                    <p>Usulan Sedang Direvisi</p>
                </div>
                <div class="icon">
                    <i class="glyphicon glyphicon-new-window"></i>
                </div>
                <a href="{{ url('seleksi-usulan') }}" class="small-box-footer">Selengkapnya <i
                        class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                    <h3>{{ $usulan_diterima }}</h3>

                    <p>Usulan Diterima</p>
                </div>
                <div class="icon">
                    <i class="glyphicon glyphicon-check"></i>
                </div>
                <a href="{{ url('monitoring-dan-evaluasi') }}" class="small-box-footer">Selengkapnya <i
                        class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{ $usulan_ditolak }}</h3>

                    <p>Usulan Ditolak</p>
                </div>
                <div class="icon">
                    <i class="glyphicon glyphicon-remove"></i>
                </div>
                <a href="{{ url('seleksi-usulan') }}" class="small-box-footer">Selengkapnya <i
                        class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col -->
    </div>
    <div class="row">

        <div class="col-lg-12 col-md-12 col-xs-12">

            <div class="box box-info">

                <div class="box-body">
                    <figure class="highcharts-figure">
                        <div id="container"></div>

                    </figure>
                </div>
            </div>



        </div>
    </div>
    <div class="row">

        <div class="col-lg-6 col-md-6 col-xs-12">

            <div class="box box-info">

                <div class="box-body">
                    <figure class="highcharts-figure">
                        <div id="container-laporan"></div>

                    </figure>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-xs-12">

            <div class="box box-info">

                <div class="box-body">
                    <figure class="highcharts-figure">
                        <div id="container-hard-laporan"></div>

                    </figure>
                </div>
            </div>



        </div>
    </div>
@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
    @include('plugins.datepicker-css')
    @include('plugins.highchart-css')
@stop

@section('js')
    @include('plugins.datepicker-js')
    @include('plugins.highchart-js')
    <script type="text/javascript">
        Highcharts.chart('container', {
            chart: {
                type: 'column'
            },
            title: {
                text: 'Jumlah Peneltian dan Pengabdian Yang Diterima dan Ditolak'
            },
            subtitle: {
                text: 'Sumber data: Database SIMLPPM'
            },
            xAxis: {
                categories: {!! json_encode($labeltahun) !!},
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Jumlah'
                }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">Tahun {point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y} Usulan</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                    name: 'Penelitian Diterima/Lolos',
                    data: {!! json_encode($jumlahpenelitianditerima) !!}

                },
                {
                    name: 'Penelitian Ditolak',
                    data: {!! json_encode($jumlahpenelitianditolak) !!}
                },
                {
                    name: 'Pengabdian Diterima/Lolos',
                    data: {!! json_encode($jumlahpengabdianditerima) !!},

                },
                {
                    name: 'Pengabdian Ditolak',
                    data: {!! json_encode($jumlahpengabdianditolak) !!}
                }
            ]
        });
    </script>
    <script type="text/javascript">
        Highcharts.chart('container-laporan', {
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Jumlah Upload Laporan'
            },
            subtitle: {
                text: 'Total usulan diterima sebanyak {{ $usulan_diterima }} usulan'
            },
            xAxis: {
                categories: ['Laporan Kemajuan', 'Laporan Akhir', 'Artikel', 'Luaran'],
                title: {
                    text: 'Jenis Laporan'
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Jumlah',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                valueSuffix: ''
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            legend: {
                layout: 'horizontal',
                align: 'right',
                verticalAlign: 'top',

                floating: false,
                borderWidth: 1,
                backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
                shadow: true
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Sudah Uplaod',
                data: {{ json_encode($upload['sudah']) }},
            }, {
                name: 'Belum Upload',
                data: {{ json_encode($upload['belum']) }},
            }]
        });
    </script>
    <script type="text/javascript">
        Highcharts.chart('container-hard-laporan', {
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Jumlah Sudah Menyerahkan Hardcopy Laporan'
            },
            subtitle: {
                text: 'Total usulan diterima sebanyak {{ $usulan_diterima }} usulan'
            },
            xAxis: {
                categories: ['Proposal', 'Laporan Kemajuan', 'Laporan Akhir', 'Artikel', 'Luaran'],
                title: {
                    text: 'Jenis Laporan'
                }
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'Jumlah',
                    align: 'high'
                },
                labels: {
                    overflow: 'justify'
                }
            },
            tooltip: {
                valueSuffix: ''
            },
            plotOptions: {
                bar: {
                    dataLabels: {
                        enabled: true
                    }
                }
            },
            legend: {
                layout: 'horizontal',
                align: 'right',
                verticalAlign: 'top',

                floating: false,
                borderWidth: 1,
                backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
                shadow: true
            },
            credits: {
                enabled: false
            },
            series: [{
                name: 'Sudah Menyerahkan',
                data: {{ json_encode($menyerahkan['sudah']) }},
            }, {
                name: 'Belum Menyerahkan',
                data: {{ json_encode($menyerahkan['belum']) }},
            }]
        });
    </script>




@stop
