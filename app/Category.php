<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  protected $table = 'categories';

  public function getRouteKeyName()
  {
    return 'slug';
  }

  public function threads()
  {
    return $this->hasMany(Thread::class);
  }
}
