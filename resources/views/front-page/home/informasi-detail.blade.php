@extends("front-page.master")
@section("konten")
<script type="text/javascript">
  $(document).ready(function(){
    $("#informasi").addClass("active");
  });
</script>
<div class='col-md-12'>
  
  <div class="home-conten-berita">
    <div class="col-lg-4 col-lg-push-8 col-xs-12">
      <h3 class="judul3">Login</h3> 
      <br>

      
      @include('layouts.form-login')
        
    </div>
    <div class="col-lg-8 col-lg-pull-4 col-xs-12">
  			
		  <h3 class="judul3">Informasi Terbaru
      </h3>  
			<div class="bs-docs-section">
			  <h3  class="page-header">
			  	<a  href="{{url('public/detail-info/'.encrypt($informasi->id_info_pendaftaran))}}">{!!$informasi->judul!!}</a>
			  	<br>
			  	<small>{!!Tanggal::time_indo($informasi->created_at)!!}</small>
			  </h3>
			  {!!$informasi->isi!!}
		  
		  </div>
					      			
    </div>
    
  </div>

</div>

@endsection
