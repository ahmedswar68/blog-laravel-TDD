@extends('layouts.app')

@section('content')
  <div class="container">
    <div class="row">
      <div class="col-md-8 col-md-offset-2">
        <div class="page-header">
          <h1>
            <avatar-form :user="{{ $profileUser }}"></avatar-form>
            <small>Since {{ $profileUser->created_at->diffForHumans() }}</small>
          </h1>
        </div>
        @forelse($activities as $date=>$items)
          <h3 class="page-header">
            {{$date}}
          </h3>
          @foreach($items as $activity)
            @if(view()->exists("profiles.activities.{$activity->type}"))
              @include("profiles.activities.{$activity->type}")
            @endif
          @endforeach
        @empty
          <p>There are no activities for this user yet!</p>
        @endforelse

      </div>
    </div>
  </div>
@endsection