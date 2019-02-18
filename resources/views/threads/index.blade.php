@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-10">
        @include ('threads._list')
        {{ $threads->render() }}
      </div>
    </div>
  </div>
@endsection
