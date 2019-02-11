<?php
/**
 * Created by PhpStorm.
 * User: swar
 * Date: 07/02/19
 * Time: 03:58 م
 */

namespace App\Traits;


use App\Activity;

trait RecordActivities
{
  protected static function bootRecordActivities()
  {
    if (auth()->guest()) return;
    foreach (static::getActivitiesToRecord() as $event) {
      static::$event(function ($model) use ($event) {
        $model->recordActivity($event);
      });
    }
    static::deleting(function ($model) {
      $model->activity()->delete();
    });
  }

  protected static function getActivitiesToRecord()
  {
    return ['created'];
  }

  protected function recordActivity($event)
  {
    $this->activity()->create([
      'user_id' => auth()->id(),
      'type' => $this->getActivityType($event)
    ]);
  }

  public function activity()
  {
    return $this->morphMany('App\Activity', 'subject');
  }

  protected function getActivityType($event)
  {
    return $event . '_' . strtolower((new \ReflectionClass($this))->getShortName());
  }
}