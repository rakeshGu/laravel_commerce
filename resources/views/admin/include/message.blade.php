@if(Session::has('error'))
    {{Session::get('error')}}
@endif

@if(Session::has('success'))
    {{Session::get('success')}}
@endif
