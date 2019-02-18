<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ReplyTest extends TestCase
{
  use DatabaseMigrations;

  /** @test */
  function it_has_an_owner()
  {
    $reply = create('App\Reply');
    $this->assertInstanceOf('App\User', $reply->owner);
  }

  /** @test */
  function it_knows_if_it_was_just_published()
  {
    $reply = create('App\Reply');
    $this->assertTrue($reply->wasJustPublished());
    $reply->created_at = Carbon::now()->subMonth();
    $this->assertFalse($reply->wasJustPublished());
  }

  /** @test */
  function it_can_detect_all_mentioned_users_in_the_body()
  {
    $reply = create('App\Reply', [
      'body' => '@swar wants to talk to @ahmed'
    ]);
    $this->assertEquals(['swar', 'ahmed'], $reply->mentionedUsers());
  }
}