<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateThreadsTest extends TestCase
{
  use RefreshDatabase;

  public function setUp()
  {
    parent::setUp();
    $this->withExceptionHandling();
    $this->signIn();
  }

  /** @test */
  function unauthorized_users_may_not_update_threads()
  {
    $thread = create('App\Thread', ['user_id' => create('App\User')->id]);
    $this->patch($thread->path(), [])->assertStatus(403);
  }

  /** @test */
  function a_thread_requires_a_title_and_description_to_be_updated()
  {
    $thread = create('App\Thread', ['user_id' => auth()->id()]);
    $this->patch($thread->path(), [
      'title' => 'Changed'
    ])->assertSessionHasErrors('description');
    $this->patch($thread->path(), [
      'body' => 'Changed'
    ])->assertSessionHasErrors('title');
  }

  /** @test */
  function a_thread_can_be_updated_by_its_creator()
  {
    $thread = create('App\Thread', ['user_id' => auth()->id()]);
    $this->patch($thread->path(), [
      'title' => 'Changed',
      'description' => 'Changed description.'
    ]);


    tap($thread->fresh(), function ($thread) {
      $this->assertEquals('Changed', $thread->title);
      $this->assertEquals('Changed description.', $thread->description);
    });
  }
}