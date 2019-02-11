<reply :attributes="{{$reply}}" inline-template v-cloak>
  <div id="reply-{{$reply->id}}" class="panel panel-default">
    <div class="card-header">
      <div class="level">
        <h5 class="flex">
          <a href="{{route('profile',$reply->owner)}}">
            {{$reply->owner->name}}
          </a>
          said since
          {{$reply->created_at->diffForHumans()}}
        </h5>
        @if(Auth::check())
          <div>
            <Favorite :reply="{{$reply}}"></Favorite>
          </div>
        @endif
      </div>
    </div>
    <div class="card-body">
      <div v-if="editing">
        <div class="form-control">
          <textarea class="form-control" v-model="body"></textarea>
        </div>
        <button class="btn btn-xs btn-primary" @click="update">Update</button>
        <button class="btn btn-xs btn-link" @click="editing=false">Cancel</button>
      </div>
      <div v-else v-text="body"></div>
    </div>
    @can('update',$reply)
      <div class="card-footer level">
        <button class="btn btn-dark btn-xs mr-1" @click="editing=true">Edit</button>
        <button class="btn btn-danger btn-xs mr-1" @click="destroy">Delete</button>

      </div>
    @endcan
  </div>
</reply>