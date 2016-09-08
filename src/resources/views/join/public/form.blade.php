@extends('layout')

@section('title')
    Join
@endsection

@section('content')
    <h1>Join</h1>

    <form method="post" action="/join">
        {{ csrf_field() }}

        <div class="form-group {{ Bootstrap::error($errors->first('name')) }}">
            <label class="control-label">Your Name</label>
            <span class="help-block">{{ $errors->first('name') }}</span>
            <input type="text" name="name" class="form-control" placeholder="Name" maxlength="255">
        </div>

        <div class="form-group {{ Bootstrap::error($errors->first('age')) }}">
            <label class="control-label">Your Age</label>
            <span class="help-block">{{ $errors->first('age') }}</span>
            <input type="number" name="age" class="form-control" placeholder="Age" />
        </div>

        <div class="form-group {{ Bootstrap::error($errors->first('location')) }}">
            <label class="control-label">Your Location</label>
            <span class="help-block">{{ $errors->first('location') }}</span>
            <input type="text" name="location" class="form-control" placeholder="Location" />
        </div>

        <div class="form-group {{ Bootstrap::error($errors->first('email')) }}">
            <label class="control-label">Your Email Address</label>
            <span class="help-block">{{ $errors->first('email') }}</span>
            <input type="text" name="email" class="form-control" placeholder="Email Address" />
        </div>

        <div class="form-group {{ Bootstrap::error($errors->first('steam')) }}">
            <label class="control-label">Your Steam Account</label>
            <span class="help-block">{{ $errors->first('steam') }}</span>
            <input type="text" name="steam" class="form-control" placeholder="Steam Account" />
        </div>

        <div class="form-group {{ Bootstrap::error($errors->first('available')) }}">
            <label class="control-label">Are you available Saturdays at {{ env('SITE_OP_TIME', '--:--') }} time?</label>
            <span class="help-block">{{ $errors->first('available') }}</span>
            <select class="form-control" name="available">
                <option value="" selected="true" style="display:none">Select</option>
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </div>

        <div class="form-group {{ Bootstrap::error($errors->first('apex')) }}">
            <label class="control-label">Do you own the Apex expansion?</label>
            <span class="help-block">{{ $errors->first('apex') }}</span>
            <select class="form-control" name="apex">
                <option value="" selected="" style="display:none">Select</option>
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </div>

        <div class="form-group {{ Bootstrap::error($errors->first('groups')) }}">
            <label class="control-label">Are you currently in another ArmA group?</label>
            <span class="help-block">{{ $errors->first('groups') }}</span>
            <select class="form-control" name="groups">
                <option value="" selected="" style="display:none">Select</option>
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>
        </div>

        <div class="form-group {{ Bootstrap::error($errors->first('experience')) }}">
            <label class="control-label">Your Arma Experience</label>
            <span class="help-block">{{ $errors->first('experience') }}</span>
            <textarea name="experience" class="form-control" placeholder="Arma Experience"></textarea>
        </div>

        <div class="form-group {{ Bootstrap::error($errors->first('bio')) }}">
            <label class="control-label">About Yourself</label>
            <span class="help-block">{{ $errors->first('bio') }}</span>
            <textarea name="bio" class="form-control" placeholder="About Yourself"></textarea>
        </div>

        <div class="form-group">
            <input type="submit" name="submit" class="btn btn-primary" value="Send Request" />
        </div>
    </form>
@endsection