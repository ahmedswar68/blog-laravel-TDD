<?php

namespace App\Providers;

use App\Category;
use Dotenv\Validator;
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
    \Validator::extend('spamfree', 'App\Rules\SpamFree@passes');
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
