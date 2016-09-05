@extends('layout')

@section('content')
    <h1>Join</h1>

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form method="post" action="/join">
        {{ csrf_field() }}

        <div class="form-group {{ $errors->first('name') != '' ? 'has-error' : ''}}">
            <label class="control-label" for="name">Your Name</label>
            <input type="text" name="name" class="form-control" id="name" placeholder="Name" maxlength="255">
            <span id="name_help" class="help-block">{{ $errors->first('name') }}</span>
        </div>

        <div class="form-group">
            <input type="number" name="age" class="form-control" placeholder="Age" />
        </div>

        <div class="form-group">
            <input type="text" name="location" class="form-control" placeholder="Location" />
        </div>

        <div class="form-group">
            <input type="text" name="email" class="form-control" placeholder="Email Address" />
        </div>

        <div class="form-group">
            <input type="text" name="steam" class="form-control" placeholder="Steam Account" />
        </div>

        <div class="form-group">
            <select class="form-control" name="available">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </div>

        <div class="form-group">
            <select class="form-control" name="apex">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </div>

        <div class="form-group">
            <select class="form-control" name="groups">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </div>

        <div class="form-group">
            <textarea name="experience" class="form-control" placeholder="Arma Experience"></textarea>
        </div>

        <div class="form-group">
            <textarea name="bio" class="form-control" placeholder="About Yourself"></textarea>
        </div>

        <div class="form-group">
            <input type="submit" name="submit" class="btn btn-primary" value="Send Request" />
        </div>
    </form>
@stop