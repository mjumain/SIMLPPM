<!doctype html>

<html lang="en">

<meta http-equiv="content-type" content="text/html;charset=UTF-8" />

<head>

    <!-- *********************** Page Title *********************** -->
    <title>SIM LPPM Universitas Muhammadiyah</title>
    <!-- *********************** meta tags ************************ -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

    <!-- Bootstrap -->


    <!-- Icons ( Font Awesome & simple line icons ) -->


    <link rel="stylesheet" type="text/css" href="{{ asset('depan/css/depan.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('depan/css/custom-login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/select2.css') }}">
    @yield('css-tambahan')
    <script type="text/javascript" src="{{ asset('depan/js/jquery.min.js') }}"></script>


</head>
<style type="text/css">
    body {
        background-position: center;
        /* Center the image */
        background-repeat: no-repeat;
        /* Do not repeat the image */
        background-size: cover;
        /* Resize the background image to cover the entire container */
    }
</style>

<body>
    <input type="hidden" class="root" value="{{ url('/') }}">
    <div class="container-fluid">
        <div class="row">

            <nav class="navbar navbar-default navbar-menu" role="navigation">

                <div class="navbar-header">

                    <a href="{{ url('/') }}">
                        <img alt="" src="{{ url('/depan/images/logolppm.png') }}" id="blue" width="130px;"
                            height="40px" style="padding-top: 10px;">
                    </a>

                    <button type="button" class="navbar-toggle" data-toggle="collapse"
                        data-target="#bs-example-navbar-collapse-1">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>


                    </button>

                    @if (auth()->check())
                        {
                        {{ auth()->user()->jenis_akun == 'dosen' ? ($url = 'dashboard-dosen') : ($url = 'dashboard') }}
                        {{-- @php(auth()->user()->jenis_akun == 'dosen') ? $url='dashboard-dosen':$url='dashboard' ;
                        @endphp --}}
                        <a class="navbar-toggle" href="{{ URL::to($url) }}">
                            <span class="fa fa-home"></span> Dashboard
                        </a>
                        }
                    @endif
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav navbar-right">
                        <li id="informasi">
                            <a href="{{ URL::to('/') }}"><em class="glyphicon glyphicon-info-sign"></em> Informasi</a>
                        </li>
                        <li id="rekap-data">
                            <a href="{{ URL::to('public/rekap-data') }}"><em class="glyphicon glyphicon-file"></em>
                                Rekap Data</a>
                        </li>

                        @if (auth()->check())
                            {
                            <li>
                                <a href="{{ URL::to($url) }}">
                                    <span class="fa fa-home"></span> Dashboard
                                </a>
                            </li>
                            }
                        @endif



                    </ul>

                </div>
            </nav>



            <div class="row">
                <div class='header' style="height: 250px;">
                    <div class="typed">
                        <div class="col-xs-12">
                            <div class="container-fluid">
                                <h2 class='judul'>
                                    SISTEM INFORMASI PENELITIAN DAN PENGABDIAN KEPADA MASYARAKAT
                                </h2>
                                <div class="title-large" style="margin-top: 30px">
                                    <div class="col-xs-12">
                                        <div class="pull-left">
                                            <img style="margin-left: 100px;"
                                                src="{{ url('/depan/images/header.png') }}" width="850px"
                                                height="130px" style="">
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="content">

                    @yield('konten')
                </div>
                <div id='online' style='clear:both'>
                </div>

            </div>

            <style type="text/css">
                #online {
                    text-align: center;
                    position: fixed;
                    bottom: 0;
                    left: 50
                }
            </style>

            <div class="text-center" id="online">
                {!! Helpers::online() !!}
            </div>
            <script type="text/javascript" src="{{ asset('depan/js/bootstrap.min.js') }}"></script>
            <script src="{{ asset('js/select2.js') }}"></script>

</body>
<script type="text/javascript">
    function baseURL() {
        var url = $(".root").val();
        return url;
    }
</script>
@yield('js-tambahan')


</html>
