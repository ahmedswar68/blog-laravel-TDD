@forelse($threads as $thread)
  <div class="card card-default">
    <div class="card-header">
      <div class="level">
        <div class="flex">
          <h4>
            <a href="/{{$thread->path()}}">
              @if(auth()->check()&&$thread->hasUpdatesFor(auth()->user()))
                <strong>{{$thread->title}}</strong>
              @else
                {{$thread->title}}
              @endif
            </a>
          </h4>
          <h5>
            Posted By: <a href="{{ route('profile', $thread->creator) }}">{{ $thread->creator->name }}</a>
          </h5>
        </div>
        <a href="{{$thread->path()}}">
          {{$thread->replies_count}}  {{str_plural('reply',$thread->replies_count)}}
        </a>
      </div>
    </div>
    <div class="card-body">
      {{$thread->description}}
    </div>
    <div class="card-footer">
      {{ $thread->visits }} Visits
    </div>
  </div>
  <hr>
@empty
  <p>there is no relevant data at this time</p>
@endforelse