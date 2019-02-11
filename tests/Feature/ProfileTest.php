<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ProfileTest extends TestCase
{
  use DatabaseMigrations;

  /** @test */
  public function a_user_has_a_profile()
  {
    $user = create('App\User');
    $this->get("/profiles/{$user->name}")
      ->assertSee($user->name);
  }

  /** @test */
  public function profile_display_all_threads_created_by_associated_user()
  {
    $this->signIn();

    $thread = create('App\Thread', ['user_id' => auth()->id()]);
    $this->get("/profiles/".auth()->user()->name  )
      ->assertSee($thread->body)
      ->assertSee($thread->title);
  }
}