@extends('layout-public')

@section('title')
    Join
@endsection

@section('content')
    <div class="content container">
        <h3>Your application has been sent</h3>
        <p>We have sent an acknowledgment email to <strong>{{ $email }}</strong>. It can take up to 1 week to process.<br />You will either receive an approved or declined email.</p>
    </div>
@endsection
