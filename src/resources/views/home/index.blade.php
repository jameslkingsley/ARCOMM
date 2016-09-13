@extends('layout-public')

@section('splash')
    YES
@endsection

@section('content')
    <div class="wrapper">
        <div class="parallax">
            <div class="parallax-background">
                <img class="parallax-background-image" src="{{ url('/images/public/banners/1.jpg') }}">
            </div>
        </div>
        <div class="section section-gray section-clients">
            <div class="container text-center">
                <h4 class="header-text">Serious, Tactical, Immersive Fun.</h4>
                <p>
                    We pride ourself on the absence of ranks and social drama. Our players range from former mil-sim players to beginners of Arma. Everyone is considered an equal member. No one holds the power to command anyone else outside of gameplay.<br>
                </p>
                <p class="highlight">
                    <strong>A reformed group with the spirit and gameplay based around the <a href="http://www.shacktac.com" style="color:#d6a600" target="_newtab">ShackTac</a> experience.</strong>
                </p>
            </div>
        </div>
        <div class="section section-presentation">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center">
                        <div class="description">
                            <h4 class="header-text">Team</h4>
                            <p>There is no formal ranking. You will never have to call someone 'sir' or adhere to unnecessary requirements. Respect is earned, not given.</p>
                        </div>
                    </div>
                    <div class="col-md-6 text-center">
                        <div class="description">
                            <h4 class="header-text">Gameplay</h4>
                            <p>We enforce first person and non-magnified/thermal optics on our servers for added immersion and to make the gameplay more challenging and fair.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 text-center">
                        <div class="description">
                            <h4 class="header-text">Missions</h4>
                            <p>Play a wide range of scenarios, both cooperative and adversarial. We don't limit ourselves to a particular faction or style, each week is different in some way.</p>
                        </div>
                    </div>
                    <div class="col-md-6 text-center">
                        <div class="description">
                            <h4 class="header-text">Discussion</h4>
                            <p>Active discussions on our Steam group. Voice your opinions and ideas, keep up-to-date with events and collaborate with other members.</p>
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                <div class="row text-center">
                    <br /><br /><br /><br />
                    <p style="font-weight: 100;font-size: 24px;line-height: 46px;">Community comes first, and the game comes second.</p>
                    <br /><br /><br /><br />
                </div>
            </div>
        </div>
        <div class="section section-presentation brand-background section-no-padding">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="description">
                            <h4 class="header-text">ARCMF</h4>
                            <p>Our mission framework is based on <a href="https://github.com/ferstaberinde/F3" target="_newtab" style="color:#FFF">F3</a> with some extra features and overhauls to improve our experience and provide robust missions.</p>
                        </div>
                    </div>
                    <div class="col-md-6 hidden-xs">
                        <img class="brand-gradient" src="{{ url('/images/public/banners/mars.png') }}"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="description">
                            <h4 class="header-text">Mars</h4>
                            <p>Mars is our total Zeus replacement providing us with a customisable editor capable of realtime mission creation and enhancement. Mars provides integrated mod support; enabling us to expand its capabilities far beyond Zeus’ limited modules.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="section section-no-padding">
            <div class="parallax">
                <div class="parallax-background">
                    <img class ="parallax-background-image" src="{{ url('/images/public/banners/3.jpg') }}"/>
                </div>
                <div class="info">
                    <h1>Submit your application!</h1>
                    <a href="{{ url('/join') }}" class="btn btn-primary btn-lg btn-fill">JOIN</a>
                </div>
            </div>
        </div>
    </div>
@endsection
