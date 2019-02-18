<?php
/**
 * Created by PhpStorm.
 * User: swar
 * Date: 17/02/19
 * Time: 10:22 م
 */

namespace App;


class Spam
{
  public function detect($body)
  {
    $this->detectInvalidKeywords($body);
    return false;
  }

  protected function detectInvalidKeywords($body)
  {
    $invalidKeywords = ['spam text'];
    foreach ($invalidKeywords as $keyword) {
      if (array_search($keyword, $body)) {
        throw new \Exception('your reply is a spam');
      }
    }
  }
}