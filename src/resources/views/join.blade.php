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

        <div class="form-group">
            <input type="text" name="name" class="form-control" placeholder="Your name" maxlength="255" />
        </div>

        <div class="form-group">
            <input type="submit" name="submit" class="btn btn-primary" value="Send Request" />
        </div>
    </form>
@stop