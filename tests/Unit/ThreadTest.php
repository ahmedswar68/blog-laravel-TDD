<?php

namespace Tests\Unit;

use App\Notifications\ThreadWasUpdated;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ThreadTest extends TestCase
{
  use DatabaseMigrations;
  protected $thread;

  public function setUp()
  {
    parent::setUp();
    $this->thread = factory('App\Thread')->create();
  }

  /** @test */
  public function a_thread_has_creator()
  {
    $this->assertInstanceOf('App\User', $this->thread->creator);
  }

  /** @test */
  public function a_thread_has_replies()
  {
    $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $this->thread->replies);
  }

  /** @test */
  public function a_thread_can_add_a_reply()
  {
    $this->thread->addReply([
      'body' => 'swar',
      'user_id' => 1
    ]);
    $this->assertCount(1, $this->thread->replies);
  }

  /** @test */
  function a_thread_notifies_all_registered_subscribers_when_a_reply_is_added()
  {
    Notification::fake();
    $this->signIn()
      ->thread
      ->subscribe()
      ->addReply([
        'body' => 'Foobar',
        'user_id' => 999
      ]);
    Notification::assertSentTo(auth()->user(), ThreadWasUpdated::class);
  }

  /** @test */
  public function a_thread_belongs_to_a_category()
  {
    $thread = make('App\Thread');
    $this->assertInstanceOf('App\Category', $thread->category);
  }

  /** @test */
  function a_thread_can_be_subscribed_to()
  {
    $thread = create('App\Thread');
    $thread->subscribe($userId = 1);
    $this->assertEquals(
      1,
      $thread->subscriptions()->where('user_id', $userId)->count()
    );
  }

  /** @test */
  function a_thread_can_be_unsubscribed_from()
  {
    $thread = create('App\Thread');
    $thread->subscribe($userId = 1);
    $thread->unsubscribe($userId);
    $this->assertCount(0, $thread->subscriptions);
  }

  /** @test */
  function a_thread_can_check_if_the_authenticated_user_has_read_all_replies()
  {
    $this->signIn();
    $thread = create('App\Thread');
    tap(auth()->user(), function ($user) use ($thread) {
      $this->assertTrue($thread->hasUpdatesFor($user));
      $user->read($thread);
      $this->assertFalse($thread->hasUpdatesFor($user));
    });
  }
}
