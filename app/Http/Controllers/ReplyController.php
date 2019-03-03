<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Reply;
use App\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ReplyController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth')->except('index');
  }

  public function index($categoryID, Thread $thread)
  {
    return $thread->replies()->paginate(20);
  }

  /**
   * Show the form for creating a new resource.
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * @param $categoryID
   * @param Thread $thread
   * @param CreatePostRequest $form
   * @return \Illuminate\Database\Eloquent\Model
   */
  public function store($categoryID, Thread $thread, CreatePostRequest $form)
  {
    if ($thread->locked) {
      return response('Thread is locked', 422);
    }

    return $thread->addReply([
      'body' => request('body'),
      'user_id' => auth()->id()
    ])->load('owner');
  }

  /**
   * @param Reply $reply
   */
  public function show(Reply $reply)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   * @param  \App\Reply $reply
   * @return \Illuminate\Http\Response
   */
  public function edit(Reply $reply)
  {
    //
  }

  /**
   * @param Reply $reply
   * @throws \Illuminate\Auth\Access\AuthorizationException
   * @throws \Illuminate\Validation\ValidationException
   */
  public function update(Reply $reply)
  {
    $this->authorize('update', $reply);
    request()->validate(['body' => 'required|spamfree']);
    $reply->update(request(['body']));
  }

  /**
   * @param Reply $reply
   * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
   * @throws \Illuminate\Auth\Access\AuthorizationException
   */
  public function destroy(Reply $reply)
  {
    $this->authorize('update', $reply);
    $reply->delete();
    if (request()->expectsJson()) {
      return response(['status' => 'Reply deleted']);
    }
    return back();
  }
}
