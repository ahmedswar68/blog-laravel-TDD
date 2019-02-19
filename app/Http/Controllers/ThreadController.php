<?php

namespace App\Http\Controllers;

use App\Thread;
use App\Category;
use App\Filters\ThreadFilters;
use App\Trending;
use Illuminate\Http\Request;

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
   * @param Request $request
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
   * @throws \Illuminate\Validation\ValidationException
   */
  public function store(Request $request)
  {
    $this->validate($request, [
      'title' => 'required',
      'description' => 'required',
      'category_id' => 'required|exists:categories,id',
    ]);

    $thread = Thread::create([
      'user_id' => auth()->id(),
      'title' => request('title'),
      'category_id' => request('category_id'),
      'description' => request('description'),
    ]);
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
   * Update the specified resource in storage.
   * @param  \Illuminate\Http\Request $request
   * @param  \App\Thread $thread
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Thread $thread)
  {
    //
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
