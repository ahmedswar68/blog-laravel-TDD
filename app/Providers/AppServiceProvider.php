<?php

namespace App\Providers;

use App\Category;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap any application services.
   * @return void
   */
  public function boot()
  {
    \View::composer('*', function ($view) {
      $categories = \Cache::rememberForever('categories', function () {
        return Category::all();
      });
      $categories = Category::all();//to be modified;
      $view->with('categories', $categories);
    });
    Schema::defaultStringLength(191);
  }

  /**
   * Register any application services.
   * @return void
   */
  public function register()
  {
    //
  }
}
