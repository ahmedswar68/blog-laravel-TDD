<?php

namespace App\Http\Controllers;

use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

class ReplyController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function index()
  {
    //
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
   * @return \Illuminate\Http\RedirectResponse
   * @throws \Illuminate\Validation\ValidationException
   */
  public function store($categoryID, Thread $thread)
  {
    $this->validate(request(), [
      'body' => 'required',
    ]);
    $reply = $thread->addReply([
      'body' => request('body'),
      'user_id' => auth()->id(),
    ]);
    if (request()->expectsJson()) {
      return $reply->load('owner');
    }
    return back()->with('flash', 'Your Reply has been Left Successfully!');;
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
   * @param Request $request
   * @param Reply $reply
   * @throws \Illuminate\Auth\Access\AuthorizationException
   */
  public function update(Reply $reply)
  {
    $this->authorize('update', $reply);
//    $reply->update(request()->validate(['body' => 'required|spamfree']));
    $reply->update(['body' => \request('body')]);
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
