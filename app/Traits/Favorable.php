<?php

namespace App\Traits;

use App\Favorite;

trait Favorable
{
  /**
   * Boot the trait.
   */
  protected static function bootFavorable()
  {
    static::deleting(function ($model) {
      $model->favorites->each->delete();
    });
  }

  public function favorite()
  {
    $attributes = ['user_id' => auth()->id()];
    if (!$this->favorites()->where($attributes)->exists())
      return $this->favorites()->create($attributes);
  }

  public function unfavorite()
  {
    $attributes = ['user_id' => auth()->id()];
    return $this->favorites()->where($attributes)->get()->each->delete();
  }

  public function favorites()
  {
    return $this->morphMany(Favorite::class, 'favorable');
  }

  public function isFavorable()
  {
    return !!$this->favorites->where('user_id', auth()->id())->count();//!! to convert it to bool
  }

  public function getIsFavorableAttribute()
  {
    return $this->isFavorable();
  }

  public function getFavoritesCountAttribute()
  {
    return $this->favorites->count();
  }
}