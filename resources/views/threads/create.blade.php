@extends('layouts.app')
@section ('head')
  <script src='https://www.google.com/recaptcha/api.js'></script>
@endsection
@section('content')
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
          <div class="panel-heading">Create a new Thread</div>
          <div class="panel-body">
            <form method="POST" action="/threads">
              {{csrf_field()}}
              <div class="form-group">
                <lable for="category_id">Choose Category :</lable>
                <select name="category_id" class="form-control" required>
                  <option>Choose Category</option>
                  @foreach ($categories as $category)
                    <option
                            value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="form-group">
                <lable for="title">Title:</lable>
                <input type="text" required id="title" value="{{old('title')}}" name="title" class="form-control">
              </div>
              <div class="form-group">
                <lable for="description">Description:</lable>
                <textarea required name="description" id="body"
                          class="form-control" rows="5" placeholder="Say something">
                  {{old('body')}}
                </textarea>
              </div>
              <div class="form-group">
                <div class="g-recaptcha" data-sitekey="6LfdOpQUAAAAADnMTDchMC9w47voMig2hJkSmDRN"></div>
              </div>
              <div class="form-group">
                <button type="submit" class="btn btn-default"> Post</button>
              </div>
              @if(count($errors))
                <ul class="alert alert-danger">
                  @foreach($errors->all() as $error)
                    <li>{{$error}}</li>
                  @endforeach
                </ul>
              @endif
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
