<?php

namespace App;

use App\Events\ThreadReceivedNewReply;
use App\Filters\ThreadFilters;
use App\Notifications\ThreadWasUpdated;
use App\Traits\RecordActivities;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
  use RecordActivities;
  protected $guarded = [];
  protected $with = ['creator', 'category'];
  protected $appends = ['isSubscribedTo'];

  protected static function boot()
  {
    parent::boot();
    static::deleting(function ($thread) {
      $thread->replies->each->delete();
    });

  }

  public function path()
  {
    return "threads/{$this->category->slug}/{$this->id}";
  }

  public function creator()
  {
    return $this->belongsTo(User::class, 'user_id');
  }

  public function category()
  {
    return $this->belongsTo(Category::class);
  }

  public function addReply($reply)
  {
    $reply = $this->replies()->create($reply);
    event(new ThreadReceivedNewReply($reply));
    return $reply;
  }

  public function replies()
  {
    return $this->hasMany(Reply::class);
  }

  /**
   * Apply all relevant thread filters.
   * @param $query
   * @param ThreadFilters $filters
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeFilter($query, ThreadFilters $filters)
  {
    return $filters->apply($query);
  }

  public function subscribe($userId = null)
  {
    $this->subscriptions()->create([
      'user_id' => $userId ?: auth()->id(),
    ]);
    return $this;
  }

  public function unsubscribe($userId = null)
  {
    $this->subscriptions()
      ->where('user_id', $userId ?: auth()->id())
      ->delete();
  }

  public function subscriptions()
  {
    return $this->hasMany(ThreadSubscription::class);
  }

  public function getIsSubscribedToAttribute()
  {
    return $this->subscriptions()->where('user_id', auth()->id())->exists();
  }

  public function hasUpdatesFor($user = null)
  {
    $user = $user ?: auth()->user();
    $key = $user->visitedThreadCacheKey($this);
    return $this->updated_at > cache($key);
  }
}
