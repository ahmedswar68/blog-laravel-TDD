<?php

namespace App;

use Carbon\Carbon;
use App\Traits\Favorable;
use App\Traits\RecordActivities;
use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
  use Favorable, RecordActivities;
  protected $guarded = [];

  protected $with = ['owner', 'favorites'];
  /**
   * this accessors to append the model's array form
   * @var array
   */
  protected $appends = ['favoritesCount', 'isFavorable', 'isBest'];

  public static function boot()
  {
    parent::boot();
    static::created(function ($reply) {
      $reply->thread->increment('replies_count');
    });
    static::deleted(function ($reply) {
      $reply->thread->decrement('replies_count');
    });
  }

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

  public function wasJustPublished()
  {
    return $this->created_at->gt(Carbon::now()->subMinute());
  }

  /**
   * Fetch all mentioned users within the reply's body.
   *
   * @return array
   */
  public function mentionedUsers()
  {
    preg_match_all('/@([\w\-]+)/', $this->body, $matches);
    return $matches[1];
  }

  public function setBodyAttribute($body)
  {
    $this->attributes['body'] = preg_replace(
      '/@([\w\-]+)/',
      '<a href="/profiles/$1">$0</a>',
      $body
    );
  }

  /**
   * Determine if the current reply is marked as the best.
   *
   * @return bool
   */
  public function isBest()
  {
    return $this->thread->best_reply_id == $this->id;
  }

  /**
   *
   * Determine if the current reply is marked as the best.
   *
   * @return bool
   */
  public function getIsBestAttribute()
  {
    return $this->isBest();
  }
}
