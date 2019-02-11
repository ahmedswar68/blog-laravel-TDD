<?php

namespace App;

use App\Traits\Favorable;
use App\Traits\RecordActivities;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
  use Favorable, RecordActivities;
  protected $guarded = [];

  protected $with = ['owner', 'favorites'];

  public function owner()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function thread()
  {
    return $this->belongsTo(Thread::class);
  }

  public function path()
  {
    return $this->thread->path() . "#reply-" . $this->id;
  }
}
