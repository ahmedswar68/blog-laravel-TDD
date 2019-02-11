@component('profiles.activities.activity')
  @slot('heading')
    <a href="/{{$activity->subject->favorable->path()}}">
      {{ $profileUser->name }} Favorited a reply </a>
  @endslot
  @slot('body')
    {{$activity->subject->body}}
  @endslot
@endcomponent
