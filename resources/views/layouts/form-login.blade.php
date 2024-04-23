<div class="panel panel-default">
  <div class="panel-body">
    <form role="form" method="POST" action="{{URL::to('login')}}"  id="form-login">
    {{csrf_field()}}

    @if(session('gagal-login'))
      <div class="help-block" id='help_error_username'>
        <div class="alert alert-danger">
          <button class="close" data-dismiss="alert">&times;</button>
          {!!session('gagal-login')!!}
        </div>
      </div>
    @endif
    <div id='div_username'>
        <span class='label-login'><b>Username</b></span>
        <input class='login-sistem' type="text" id="username" name="username"  placeholder="Username berupa NIDN/NIP" maxlength='30' required="">
        
      
    </div>
    <br>
    <br>
    <div  id='div_password'>
        <span class='label-login'><b>Password</b></span>

        <input type="password" id="password" name="password" required=""  placeholder="Masukan Password" class='login-sistem'>
        
        <div class='text-right' style='padding-top:40px;'>
             <button class='btn btn-login radius0' ><em class='fa fa-sign-in'></em> Login</button>
         </div>
     </div>
 

 
    </form>
  </div>
</div>
<style type="text/css">
  .login-sistem{
    font-size: 15px;
    margin-top: 15px;
  }
</style>
