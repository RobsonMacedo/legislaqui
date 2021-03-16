@if (Session::has('flash_msg'))
    <div class="alert alert-info">{{ Session::get('flash_msg') }}</div>
@endif

@if (Session::has('error_msg'))
    <div class="alert alert-danger">{{ Session::get('error_msg') }}</div>
@endif

@if(Session::has('proposal_crud_msg'))
    <div class="alert alert-info">{{ Session::get('proposal_crud_msg') }}</div>
@endif
