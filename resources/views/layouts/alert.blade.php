<div class="alert alert-{!! Session::get('alert')['type'] !!}">
    <button class="close" data-dismiss="alert">&times;</button>
    <b>
        @if (Session::get('alert')['type'] == 'success')
            <i class="glyphicon glyphicon-ok-sign"></i> Success!
        @endif
        @if (Session::get('alert')['type'] == 'info')
            <i class="glyphicon glyphicon-info-sign"></i> Info!
        @endif
        @if (Session::get('alert')['type'] == 'warning')
            <i class="glyphicon glyphicon-info-sign"></i> Warning!
        @endif
        @if (Session::get('alert')['type'] == 'danger')
            <i class="glyphicon glyphicon-remove-sign"></i> Fail!
        @endif
    </b>
    <br>
    <div style="padding-left: 15px">
        {!! Session::get('alert')['message'] !!}
    </div>

</div>
