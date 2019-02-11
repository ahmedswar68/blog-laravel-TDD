<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CategoryTest extends TestCase
{
  use DatabaseMigrations;

  /** @test */
  public function a_category_consists_of_threads()
  {
    $category = create('App\Category');
    $thread = create('App\Thread', ['category_id' => $category->id]);
    $this->assertTrue($category->threads->contains($thread));
  }
}
