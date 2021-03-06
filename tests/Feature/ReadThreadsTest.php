<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ReadThreadsTest extends TestCase
{
  use DatabaseMigrations;
  protected $thread;

  public function setUp()
  {
    parent::setUp();
    $this->thread = create('App\Thread');
  }

  /** @test */
  public function a_user_can_browse_all_threads()
  {
    $this->get('/threads')->assertSee($this->thread->title);
  }

  /** @test */
  public function a_user_can_browse_a_single_threads()
  {
    $this->get($this->thread->path())
      ->assertSee($this->thread->title);
  }

  /** @test */
  public function a_user_can_filter_threads_according_to_a_category()
  {
    $category = create('App\Category');
    $threadInChannel = create('App\Thread', ['category_id' => $category->id]);
    $threadNotInChannel = create('App\Thread');

    $this->get('threads/' . $category->slug)
      ->assertSee($threadInChannel->titile)//      ->assertDontSee($threadNotInChannel->titile)
    ;
  }

  /** @test */
  public function a_user_can_filter_threads_by_any_username()
  {
    $this->signIn(create('App\User', ['name' => 'AhmedSwar']));
    $threadBySwar = create('App\Thread', ['user_id' => auth()->id()]);
    $threadNotBySwar = create('App\Thread');

    $this->get('threads?by=AhmedSwar')
      ->assertSee($threadBySwar->titile)//            ->assertDontSee($threadNotBySwar->titile)
    ;
  }

  /** @test */
  public function a_user_can_filter_threads_popularity()
  {
    $threadWithTwoReplies = create('App\Thread');
    create('App\Reply', ['thread_id' => $threadWithTwoReplies->id], 2);

    $threadWithThreeReplies = create('App\Thread');
    create('App\Reply', ['thread_id' => $threadWithThreeReplies->id], 3);

    $this->thread;// thread With No Replies

    $response = $this->getJson('/threads?popular=1')->json();
    $this->assertEquals([3, 2, 0], array_column($response['data'], 'replies_count'));
  }

  /** @test */
  function a_user_can_filter_threads_by_those_that_are_unanswered()
  {
    $thread = create('App\Thread');
    create('App\Reply', ['thread_id' => $thread->id]);
    $response = $this->getJson('threads?unanswered=1')->json();
    $this->assertCount(1, $response['data']);
  }

  /** @test */
  function a_user_can_request_all_replies_for_a_given_thread()
  {
    $thread = create('App\Thread');
    create('App\Reply', ['thread_id' => $thread->id], 2);
    $response = $this->getJson($thread->path() . '/replies')->json();
    $this->assertCount(2, $response['data']);
    $this->assertEquals(2, $response['total']);
  }

  /** @test */
  function we_record_a_new_visit_each_time_the_thread_is_read()
  {
    $thread = create('App\Thread');
    $this->assertSame(0, $thread->visits);
    $this->call('GET', $thread->path());
    $this->assertEquals(1, $thread->fresh()->visits);
  }
}
