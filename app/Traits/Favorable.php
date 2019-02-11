<?php

namespace App\Traits;

use App\Favorite;

trait Favorable
{
  public function favorite()
  {
    $attributes = ['user_id' => auth()->id()];
    if (!$this->favorites()->where($attributes)->exists())
      return $this->favorites()->create($attributes);
  }

  public function favorites()
  {
    return $this->morphMany(Favorite::class, 'favorable');
  }

  public function isFavorable()
  {
    return !!$this->favorites->where('user_id', auth()->id())->count();//!! to convert it to bool
  }
}