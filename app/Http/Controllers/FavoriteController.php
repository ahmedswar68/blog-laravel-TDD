<?php

namespace App\Http\Controllers;

use App\Favorite;
use App\Reply;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }

  /**
   * Display a listing of the resource.
   * @return \Illuminate\Http\Response
   */
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
   * @param Reply $reply
   * @return \Illuminate\Database\Eloquent\Model
   */
  public function store(Reply $reply)
  {
    $reply->favorite();
    return back();
  }

  /**
   * Display the specified resource.
   * @param  \App\Favorite $favorite
   * @return \Illuminate\Http\Response
   */
  public function show(Favorite $favorite)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   * @param  \App\Favorite $favorite
   * @return \Illuminate\Http\Response
   */
  public function edit(Favorite $favorite)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   * @param  \Illuminate\Http\Request $request
   * @param  \App\Favorite $favorite
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Favorite $favorite)
  {
    //
  }

  /**
   * @param Reply $reply
   */
  public function destroy(Reply $reply)
  {
    $reply->unfavorite();
  }
}
