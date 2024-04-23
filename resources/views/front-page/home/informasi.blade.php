@extends("front-page.master")
@section("konten")
<script type="text/javascript">
  $(document).ready(function(){
    $("#informasi").addClass("active");
  });
</script>
<div class='col-md-12'>
  
  <div class="home-conten-berita">
    <div class="col-md-4 col-md-push-8 col-xs-12">
      <h3 class="judul3">Login</h3> 
      <br>

      
      @include('layouts.form-login')
        
    </div>
    <div class="col-md-8 col-md-pull-4 col-xs-12">
  		<h3 class="judul3">Informasi Terbaru</h3>  
  			@foreach($informasi as $p)
  			<div class="bs-docs-section">
  			  <h3 class="judulberita"  class="page-header" style="font-size: 100%;">
  			  	<a  href="{{url('public/detail-info/'.encrypt($p->id_info_pendaftaran))}}">{!!$p->judul!!}</a>
  			  	<br>
  			  	<small>{!!Tanggal::time_indo($p->created_at)!!}</small>
  			  </h3>
			  </div>
			@endforeach
      <div class='text-center'>{{$informasi->links()}}</div>
		</div>
    

  </div>
    

</div>

@endsection
