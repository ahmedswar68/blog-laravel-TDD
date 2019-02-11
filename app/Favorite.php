<?php

namespace App;

use App\Traits\RecordActivities;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
  use RecordActivities;
  protected $guarded = [];

  public function favorable()
  {
    return $this->morphTo();
  }
}
