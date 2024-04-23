@extends('adminlte::page')

@section('title', 'Input naggota')

@section('content_header')
    <h1>Input Anggota</h1>
    <ol class="breadcrumb">
      <li><a href="{{url('pendaftaran-usulan')}}"><i class="fa fa-wrench"></i> Pendaftaran Usulan</a></li>
      <li class="active"> Input Anggota</li>
    </ol>
@stop

@section('content')
  <div class="row">
    <div class="col-lg-12 col-xs-12">
      @if(Session::has('alert'))
        @include('layouts.alert')
      @endif
      <div class="box box-info">
        
        <div class="box-body">
         
          @include('layouts.data-usulan-incl')
           <form id="form-anggota" action="{{URL::to('/pendaftaran-usulan/input-anggota/'.encrypt($data->id_usulan))}}" method="post">
              <div class="row">
                <div class="col-xs-12">
                  <h3><i class="glyphicon glyphicon-user"></i>Anggota Kegiatan</h3>
                  
                  <div class="table-responsive">
                    <table class="table table-striped">
                      {{csrf_field()}}
                      @foreach($data->anggota as $no => $anggota)
                      <tr>
                          <td width="13%">Dosen Anggota {{$no+1}}</td>
                          <input type="hidden" name="id_pelaksana[]" value="{{$anggota->id_pelaksana}}">

                          <td width="36%">
                              <select name="id_peg[]" class="form-control select2" width="100%" >
                              <option value=""></option>
                              @if($anggota->pegawai)
                              <option value="{{$anggota->id_peg}}" selected="">{{$anggota->pegawai->nip." ".Helpers::nama_gelar($anggota->pegawai)}}</option>
                              @endif

                              </select>
                          </td>
                          <td width="8%">Konfirmasi</td>
                          <td width="1%">:</td>
                          <td width="36%">{!!Helpers::konfirmasi($anggota)!!}</td>
                      </tr>
                      
                      @endforeach
                         
                    </table>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-xs-12">
                  <h3><i class="glyphicon glyphicon-user"></i>Mahasiswa Anggota Kegiatan</h3>
                  <div class="table-responsive">
                    <table class="table table-striped" id="anggotaMahasiswa">
                        
                  
                        @forelse($data->pelaksana_mahasiswa as $m)
                        <tr>
                            <td width="13%">Anggota Mahasiswa 

                            </td>
                            <td >
                                <input type="text" name="nim[]" value="{{$m->nim}}" class="form-control" placeholder="Nomor Induk Mahasiswa">
                                (kosongkan jika tidak ada)
                            </td>
                            <td >
                                <input type="text" name="nama_mahasiswa[]" value="{{$m->nama_mahasiswa}}" class="form-control" placeholder="Nama Mahasiswa">
                                (kosongkan jika tidak ada)
                            </td>
                            <td>
                              <select name="prodi_id[]" class="form-control" width="100%" >
                              
                                @foreach(App\Prodi::get() as $prodi)
                                <option value="{{$prodi->id_prodi}}" {{$prodi->id_prodi==$m->prodi_id ? 'selected':''}}>{{"(".$prodi->jenjang.") ".$prodi->nama_prodi}}</option>
                                @endforeach
                              </select>
                              
                            </td>
                            
                            
                        </tr>
                        @empty
                        <tr>
                            <td width="13%">Anggota Mahasiswa 

                            </td>
                            <td >
                                <input type="text" name="nim[]" class="form-control" placeholder="Nomor Induk Mahasiswa">
                                (kosongkan jika tidak ada)
                            </td>
                            <td >
                                <input type="text" name="nama_mahasiswa[]" class="form-control" placeholder="Nama Mahasiswa">
                                (kosongkan jika tidak ada)
                            </td>
                            <td>
                              <select name="prodi_id[]" class="form-control" width="100%" >
                              
                              @foreach(App\Prodi::get() as $prodi)
                                <option value="{{$prodi->id_prodi}}">{{"(".$prodi->jenjang.") ".$prodi->nama_prodi}}</option>
                              @endforeach
                              </select>
                                
                            </td>
                            
                            
                        </tr>
                        @endforelse
                      
                        
                        
                      
                      
                         
                    </table>
                    <table>
                      <tr>
                          <td colspan="5">
                            <button type="button"  class="btn btn-sm btn-success add_field_button"><i class="glyphicon glyphicon-plus"></i> Tambah Anggota Mahasiswa</button>
                          </td>
                          <td>
                            &nbsp;
                            &nbsp;
                             <button type="button"  class="btn btn-sm btn-danger remove_mhs"><i class="glyphicon glyphicon-times"></i> Hapus Anggota Mahasiswa</button>
                          </td>
                        </tr>
                    </table>
                  </div>
                </div>
              </div>
              <div class="row">
                  <div class="col-lg-offset-4 col-lg-4">
                      <button class="btn btn-primary btn-block" type="button" onclick="simpananggota()">Simpan</button>
                  </div>
              </div>

          </form>
          <hr>
          * ketikan nidn atau nama dosen
        </div>
      </div>
    </div>
  </div>
  <template id="template">
    <tr>
      <td width="13%">Anggota Mahasiswa 

      </td>
      <td >
          <input type="text" name="nim[]" class="form-control" placeholder="Nomor Induk Mahasiswa">
          (kosongkan jika tidak ada)
      </td>
      <td >
          <input type="text" name="nama_mahasiswa[]" class="form-control" placeholder="Nama Mahasiswa">
          (kosongkan jika tidak ada)
      </td>
      <td>
        <select name="prodi_id[]" class="form-control" width="100%" >
        
        @foreach(App\Prodi::get() as $prodi)
          <option value="{{$prodi->id_prodi}}">{{"(".$prodi->jenjang.") ".$prodi->nama_prodi}}</option>
        @endforeach
        </select>
          
      </td>
      
      
    </tr>
  </template>
@stop

@section('css')
  @include('plugins.alertify-css')
  @include('plugins.icon-picker-css')
@stop

@section('js')
  @include('plugins.alertify-js')
  <script type="text/javascript">
    $(".select2").select2({
      placeholder:"Tulis nama atau nidn dosen",
      
      ajax:{
          url:"{{url('load-dosen-pegawai')}}",
          dataTyper:"json",
          data:function(param)
          {
              var value= {
                  search:param.term,
              };
              return value;
          },
          processResults:function(hasil)
          {
              return {
                  results:hasil,
              };
          }
      }
    });
    $('.select2').on('select2:close', function (e) {
          var data = $(this).val();
          var selector=$(this);
          $.ajax({
            url:'{{url('cek-dosen-tersedia')}}',
            type:'get',
            data:{
              id_peg:data,
              jenis_usulan_id:"{{$data->buka_penerimaan->jenis_usulan_id}}",
              skim_id:"{{$data->buka_penerimaan->skim_id}}"
            },
            success:function(res){
              if (res.boleh==0) {
                selector.empty();
                alertify.alert('Info!',res.status);
              }
            }

          })
         
      });
    function simpananggota()
    {
        alertify.confirm("Konfirmasi","Pastikan data dosen dan mahasiswa telah diisi dengan benar, Simpan anggota yang ditambahkan?",
        function(){
          
          $("#form-anggota").submit();
          
          
        },
        function(){
          alertify.error("Batal Menyimpan");
        });
        
    }
    var max_fields      = 100; //maximum input boxes allowed
    var add_button      = $(".add_field_button"); //Add button ID
    var template=$("#template").html();

    var x = 1; //initlal text box count
    
    $(add_button).click(function(e){ //on add input button click
      e.preventDefault();
      if(x < max_fields){ //max input box allowed
        x++; //text box increment
        //alert(template);
        $('#anggotaMahasiswa tr:last').after(template);
        
      }
    });
    
    $('.remove_mhs').click(function(){
 
      $('#anggotaMahasiswa tr:last').remove(); 
     });

    
  </script>
 
@stop