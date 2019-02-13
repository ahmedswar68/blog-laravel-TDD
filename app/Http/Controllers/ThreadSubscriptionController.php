<?php

namespace App\Http\Controllers;

use App\Thread;
use App\ThreadSubscription;
use Illuminate\Http\Request;

class ThreadSubscriptionController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a new thread subscription.
   *
   * @param int $categoryId
   * @param Thread $thread
   */
  public function store($categoryId, Thread $thread)
  {
    $thread->subscribe();
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\ThreadSubscription $threadSubscription
   * @return \Illuminate\Http\Response
   */
  public function show(ThreadSubscription $threadSubscription)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\ThreadSubscription $threadSubscription
   * @return \Illuminate\Http\Response
   */
  public function edit(ThreadSubscription $threadSubscription)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request $request
   * @param  \App\ThreadSubscription $threadSubscription
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, ThreadSubscription $threadSubscription)
  {
    //
  }

  /**
   * Delete an existing thread subscription.
   *
   * @param int $channelId
   * @param Thread $thread
   */
  public function destroy($categoryId, Thread $thread)
  {
    $thread->unsubscribe();
  }
}
