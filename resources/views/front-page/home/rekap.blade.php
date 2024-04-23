@extends('front-page.master')
@section('konten')
    <script type="text/javascript">
        $(document).ready(function() {
            $("#rekap-data").addClass("active");
        });
    </script>
    <div class='col-md-12'>

        <div class="home-conten-berita">
            <div class="col-lg-12 col-md-12 col-xs-12">

                <h3 class="judul3">Rekap Data
                </h3>
                <div class="bs-docs-section">
                    <form class="form-horizontal" action="{{ url('public/rekap-data') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label class="control-label col-lg-2 ">Tahun Anggaran</label>
                            <div class="col-lg-8">
                                <select class="form-control" name="tahun_anggaran_id">
                                    <option value="">Semua</option>
                                    @foreach (App\TahunAnggaran::orderBy('tahun', 'desc')->get() as $ta)
                                        <option value="{{ $ta->id_tahun_anggaran }}">{{ $ta->tahun }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-lg-2 ">Jenis Usulan</label>
                            <div class="col-lg-8">
                                <select class="form-control" name="jenis_usulan_id">
                                    <option value="">Semua</option>
                                    @foreach (App\JenisUsulan::get() as $t)
                                        <option value="{{ $t->id_jenis_usulan }}">{{ $t->jenis_usulan }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-lg-2 ">Sumber Dana</label>
                            <div class="col-lg-8">
                                <select class="form-control" name="asal_dana_id">
                                    <option value="">Semua</option>
                                    @foreach (App\SumberDana::get() as $t)
                                        <option value="{{ $t->id_sumber_dana }}">{{ $t->nama_sumber_dana }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-lg-2 ">Skim</label>
                            <div class="col-lg-8">
                                <select class="form-control" name="skim_id">
                                    <option value="">Semua</option>
                                    @foreach (App\Skim::get() as $t)
                                        <option value="{{ $t->id_skim }}">{{ $t->nama_skim }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-lg-2 ">Dosen Peneliti/Pengabdi</label>
                            <div class="col-lg-8">
                                <select class="form-control select2" name="id_peg">
                                    <option value="">Semua</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-lg-offset-2 col-lg-8">
                                <button class="btn btn-login"> Ekspor Excel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js-tambahan')

    <script type="text/javascript">
        $(".select2").select2({
            placeholder: "Tulis nama atau nidn dosen",
            allowClear: true,
            ajax: {
                url: "{{ url('load-dosen-pegawai-public') }}",
                dataType: "json",
                data: function(param) {
                    var value = {
                        search: param.term,
                    };
                    return value;
                },
                processResults: function(hasil) {
                    return {
                        results: hasil,
                    };
                }
            }
        });
    </script>
@stop
