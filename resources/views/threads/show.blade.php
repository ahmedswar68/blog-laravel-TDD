@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-8">
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="level">
              <span class="flex">
                <a href="{{route('profile',$thread->creator)}}">
                    {{$thread->creator->name}}
                </a>
                posted : <a href="{{$thread->path()}}">
                  {{$thread->title}}
                </a>
              </span>
              @can('update',$thread)
                <form method="POST" action="{{url($thread->path())}}">
                  {{csrf_field()}}
                  {{method_field('DELETE')}}
                  <button type="submit" class="btn btn-link">Delete Thread</button>
                </form>
              @endcan
            </div>
          </div>
          <div class="panel-body">
            {{$thread->description}}
          </div>
        </div>
        @foreach($replies as $reply)
          <div class="panel panel-default">
            <div class="panel-heading">
              <div class="level">
                <h5 class="flex">
                  <a href="{{route('profile',$reply->owner)}}">
                    {{$reply->owner->name}}
                  </a>
                  said since
                  {{$reply->created_at->diffForHumans()}}
                </h5>
                <div>
                  <form method="POST" action="/replies/{{$reply->id.'/favorites'}}">
                    {{csrf_field()}}
                    <button type="submit" class="btn btn-primary"
                            {{$reply->isFavorable()?'disabled':''}}>
                      {{$reply->favorites_count}} {{str_plural('Favorite',$reply->favorites_count)}}
                    </button>

                  </form>
                </div>
              </div>
            </div>
            <div class="panel-body">
              {{$reply->body}}
            </div>
          </div>
        @endforeach
        {{ $replies->links() }}
        @if(auth()->check())
          <form method="POST" action="/{{$thread->path().'/replies'}}">
            {{csrf_field()}}
            <div class="form-group">
              <textarea name="body" id="body" class="form-control" placeholder="Say something"></textarea>
            </div>
            <button type="submit" class="btn btn-primary"> Post</button>

          </form>

        @else
          <p class="">
            Please <a href="{{route('login')}}">sign in</a> to participate in this discussion
          </p>
        @endif
      </div>
      <div class="col-md-4">
        <div class="panel panel-default">
          <div class="panel-body">
            <p>
              This thread was published {{$thread->created_at->diffForHumans()}} by
              <a href="#">{{$thread->creator->name}}</a>, and currently has
              {{ $thread->replies_count }} {{str_plural('comment',$thread->replies_count)}}
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
