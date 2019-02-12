<?php

namespace App;

use App\Filters\ThreadFilters;
use App\Traits\RecordActivities;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
  use RecordActivities;
  protected $guarded = [];
  protected $with = ['creator', 'category'];

  protected static function boot()
  {
    parent::boot();
    static::addGlobalScope('replyCount', function ($builder) {
      $builder->withCount('replies');
    });
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
    return $this->replies()->create($reply);
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
}
