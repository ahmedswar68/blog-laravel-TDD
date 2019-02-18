@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-10">
        @forelse($threads as $thread)
          <div class="card card-default">
            <div class="card-header">
              <div class="level">
                <h4 class="flex">
                  <a href="/{{$thread->path()}}">
                    @if(auth()->check()&&$thread->hasUpdatesFor(auth()->user()))
                      <strong>{{$thread->title}}</strong>
                    @else
                      {{$thread->title}}
                    @endif
                  </a>
                </h4>
                <a href="{{$thread->path()}}">
                  {{$thread->replies_count}}  {{str_plural('reply',$thread->replies_count)}}
                </a>
              </div>
            </div>
            <div class="card-body">
              {{$thread->description}}
            </div>
          </div>
          <hr>
        @empty
          <p>there is no relevant data at this time</p>
        @endforelse
      </div>
    </div>
  </div>
@endsection
