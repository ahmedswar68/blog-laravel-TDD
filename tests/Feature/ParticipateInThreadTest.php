<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ParticipateInThreadTest extends TestCase
{
  use DatabaseMigrations;

  /** @test */
  public function unauthenticated_users_may_not_add_replies()
  {
    $this->withExceptionHandling()->post('threads/Category/1/replies', [])
      ->assertRedirect('/login');
  }

  /** @test */
  public function an_authenticated_user_may_participate_in_forum_thread()
  {
    $this->signIn();
    $thread = create('App\Thread');
    $reply = make('App\Reply');
    $this->post($thread->path() . '/replies', $reply->toArray());
    $this->get($thread->path())
      ->assertSee($reply->body);
  }

  /** @test */
  public function a_reply_requires_a_body()
  {
    $this->withExceptionHandling()->signIn();

    $thread = create('App\Thread');
    $reply = make('App\Reply', ['body' => null]);
    $this->post($thread->path() . '/replies', $reply->toArray())
      ->assertSessionHasErrors('body');
  }

  /** @test */
  function unauthorized_users_cannot_delete_replies()
  {
    $this->withExceptionHandling();
    $reply = create('App\Reply');
    $this->delete("/replies/{$reply->id}")
      ->assertRedirect('login');
    $this->signIn()
      ->delete("/replies/{$reply->id}")
      ->assertStatus(403);
  }

  /** @test */
  function authorized_users_can_delete_replies()
  {
    $this->signIn();
    $reply = create('App\Reply', ['user_id' => auth()->id()]);
    $this->delete("/replies/{$reply->id}")->assertStatus(302);
    $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    $this->assertEquals(0, $reply->thread->fresh()->replies_count);
  }

  /** @test */
  function unauthorized_users_cannot_update_replies()
  {
    $this->withExceptionHandling();
    $reply = create('App\Reply');
    $this->patch("/replies/{$reply->id}")
      ->assertRedirect('login');
    $this->signIn()
      ->patch("/replies/{$reply->id}")
      ->assertStatus(403);
  }

  /** @test */
  function authorized_users_can_update_replies()
  {
    $this->signIn();
    $reply = create('App\Reply', ['user_id' => auth()->id()]);
    $updatedReply = 'You been updated this reply.';
    $this->patch("/replies/{$reply->id}", ['body' => $updatedReply]);
    $this->assertDatabaseHas('replies', ['id' => $reply->id, 'body' => $updatedReply]);
  }
}