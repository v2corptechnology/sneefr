@if (Session::has('success'))
  <div class="alert alert-success alert-dismissible fade in" role="alert">
    <div class="container">
      <button type="button" class="close" data-dismiss="alert">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
      </button>
      {!! Session::get('success') !!}
    </div>
  </div>
@endif

@if (Session::has('error'))
  <div class="alert alert-danger alert-dismissible fade in" role="alert">
    <div class="container">
      <button type="button" class="close" data-dismiss="alert">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
      </button>
      {!! Session::get('error') !!}
    </div>
  </div>
@endif

@if (Session::has('warning'))
  <div class="alert alert-warning alert-dismissible fade in" role="alert">
    <div class="container">
      <button type="button" class="close" data-dismiss="alert">
        <span aria-hidden="true">&times;</span>
        <span class="sr-only">Close</span>
      </button>
      {!! Session::get('warning') !!}
    </div>
  </div>
@endif