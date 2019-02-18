<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class MentionUsersTest extends TestCase
{
  use DatabaseMigrations;

  /** @test */
  function mentioned_users_in_a_reply_are_notified()
  {
    // Given we have a user, Swar, who is signed in.
    $john = create('App\User', ['name' => 'Swar']);
    $this->signIn($john);
    // And we also have a user, Ahmed.
    $jane = create('App\User', ['name' => 'Ahmed']);
    // If we have a thread
    $thread = create('App\Thread');
    // And Swar replies to that thread and mentions @Ahmed.
    $reply = make('App\Reply', [
      'body' => 'Hey @Ahmed check this out.'
    ]);
    $this->json('post', $thread->path() . '/replies', $reply->toArray());
    // Then @Ahmed should receive a notification.
    $this->assertCount(1, $jane->notifications);
  }

  /** @test */
  function it_can_fetch_all_mentioned_users_starting_with_the_given_characters()
  {
    create('App\User', ['name' => 'Swar']);
    create('App\User', ['name' => 'Swar2']);
    create('App\User', ['name' => 'Ahmed']);
    $results = $this->json('GET', '/api/users', ['name' => 'ahmed']);
    $this->assertCount(2, $results->json());
  }
}