<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Category;
use App\Filters\ThreadFilters;
use App\Trending;
use App\Rules\Recaptcha;

class ThreadController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth')->except(['index', 'show']);
  }

  /**
   * @param Category $category
   * @param ThreadFilters $filters
   * @param Trending $trending
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function index(Category $category, ThreadFilters $filters, Trending $trending)
  {

    $threads = $this->getThreads($category, $filters);

    if (request()->wantsJson()) {
      return $threads;
    }
    return view('threads.index', [
      'threads' => $threads,
      'trending' => $trending->get()
    ]);
  }

  protected function getThreads(Category $category, ThreadFilters $filters)
  {
    $threads = Thread::latest()->filter($filters);
    if ($category->exists) {
      $threads->where('category_id', $category->id);
    }
    return $threads->paginate(25);
  }

  /**
   * Show the form for creating a new resource.
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('threads.create');
  }

  /**
   * @param Recaptcha $recaptcha
   * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
   * @throws \Illuminate\Validation\ValidationException
   */
  public function store(Recaptcha $recaptcha)
  {

    request()->validate([
      'title' => 'required',
      'description' => 'required',
      'category_id' => 'required|exists:categories,id',
      'g-recaptcha-response' => ['required', $recaptcha]
    ]);

    $thread = Thread::create([
      'user_id' => auth()->id(),
      'title' => request('title'),
      'category_id' => request('category_id'),
      'description' => request('description'),
    ]);
    if (request()->wantsJson()) {
      return response($thread, 201);
    }
    return redirect($thread->path())->with('flash', 'Your Thread has been published Successfully!');
  }

  /**
   * @param $categoryID
   * @param Thread $thread
   * @param Trending $trending
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
   */
  public function show($categoryID, Thread $thread, Trending $trending)
  {
    if (auth()->check()) {
      auth()->user()->read($thread);
    }
    $trending->push($thread);
    $thread->increment('visits');
    return view('threads.show', compact('thread'));
  }

  /**
   * Show the form for editing the specified resource.
   * @param  \App\Thread $thread
   * @return \Illuminate\Http\Response
   */
  public function edit(Thread $thread)
  {
    //
  }

  /**
   * @param $category
   * @param Thread $thread
   * @return Thread
   * @throws \Illuminate\Auth\Access\AuthorizationException
   */
  public function update($category, Thread $thread)
  {

    $this->authorize('update', $thread);

    $thread->update(request()->validate([
      'title' => 'required',
      'description' => 'required'
    ]));
    return $thread;
  }

  /**
   * @param $category
   * @param Thread $thread
   * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
   * @throws \Exception
   */
  public function destroy($category, Thread $thread)
  {
    $this->authorize('update', $thread);
    $thread->delete();
    if (\request()->wantsJson()) {
      return response([], 204);
    }
    return redirect('/threads');
  }
}
