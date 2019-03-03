<?php

namespace Tests\Feature;

use App\Activity;
use App\Thread;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use App\Rules\Recaptcha;

class CreateThreadTest extends TestCase
{
  use DatabaseMigrations, MockeryPHPUnitIntegration;
  protected $thread;

  public function setUp()
  {
    parent::setUp();
    app()->singleton(Recaptcha::class, function () {
      return \Mockery::mock(Recaptcha::class, function ($m) {
        $m->shouldReceive('passes')->andReturn(true);
      });
    });
  }

  /** @test */
  public function guests_may_not_create_threads()
  {
    $this->withExceptionHandling();
    $this->get('/threads/create')
      ->assertRedirect(route('login'));
    $this->post(route('threads'))
      ->assertRedirect(route('login'));
  }

  /** @test */
  function new_users_must_first_confirm_their_email_address_before_creating_threads()
  {
    $user = factory('App\User')->states('unconfirmed')->create();
    $this->signIn($user);
    $thread = make('App\Thread');
    $this->post(route('threads'), $thread->toArray())
      ->assertRedirect(route('threads'))
      ->assertSessionHas('flash', 'You must first confirm your email address.');
  }

  /** @test */
  public function a_user_can_create_new_forum_threads()
  {
    $response = $this->publishThread(['title' => 'Some Title', 'description' => 'Some body.']);
    $this->get($response->headers->get('Location'))
      ->assertSee('Some Title')
      ->assertSee('Some body.');
  }

  /** @test */
  public function a_thread_requires_a_title()
  {
    $this->publishThread(['title' => null])
      ->assertSessionHasErrors('title');
  }

  /** @test */
  public function authorized_users_can_delete_threads()
  {
    $this->signIn();
    $thread = create('App\Thread', ['user_id' => auth()->id()]);
    $reply = create('App\Reply', ['thread_id' => $thread->id]);
    $response = $this->json('DELETE', $thread->path());
    $response->assertStatus(204);
    $this->assertDatabaseMissing('threads', ['id' => $thread->id]);
    $this->assertDatabaseMissing('replies', ['id' => $reply->id]);
    $this->assertEquals(0, count(Activity::all()->toArray()));
  }

  /** @test */
  public function un_authorized_users_cannot_delete_threads()
  {
    $this->withExceptionHandling();
    $thread = create('App\Thread');
    $this->delete($thread->path())->assertRedirect(route('login'));
    $this->signIn();
    $this->delete($thread->path())->assertStatus(403);
  }

  public function publishThread($overrides = [])
  {
    $this->withExceptionHandling()->signIn();
    $thread = make('App\Thread', $overrides);
    return $this->post('/threads', $thread->toArray() + ['g-recaptcha-response' => 'token']);
  }

  /** @test */
  public function an_thread_requires_a_description()
  {
    $this->publishThread(['description' => null])
      ->assertSessionHasErrors('description');
  }

  /** @test */
  public function a_thread_requires_a_valid_category()
  {
    factory('App\Category', 2)->create();
    $this->publishThread(['category_id' => null])
      ->assertSessionHasErrors('category_id');
    $this->publishThread(['category_id' => 999])
      ->assertSessionHasErrors('category_id');
  }

  /** @test */
  function a_thread_requires_a_unique_slug()
  {
    $this->signIn();
    $thread = create('App\Thread', ['title' => 'Foo Title']);
    $this->assertEquals($thread->slug, 'foo-title');
    $thread = $this->postJson(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'token'])->json();
    $this->assertEquals("foo-title-{$thread['id']}", $thread['slug']);
  }

  /** @test */
  function a_thread_with_a_title_that_ends_in_a_number_should_generate_the_proper_slug()
  {
    $this->signIn();
    $thread = create('App\Thread', ['title' => 'Some Title 24']);
    $thread = $this->postJson(route('threads'), $thread->toArray() + ['g-recaptcha-response' => 'token'])->json();
    $this->assertEquals("some-title-24-{$thread['id']}", $thread['slug']);
  }

  /** @test */
  function a_thread_requires_recaptcha_verification()
  {
    unset(app()[Recaptcha::class]);
    $this->publishThread(['g-recaptcha-response' => 'test'])
      ->assertSessionHasErrors('g-recaptcha-response');
  }
}
