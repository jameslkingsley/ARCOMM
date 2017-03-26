@extends('layout-public')

@section('splash')
    YES
@endsection

@section('content')
    <div class="wrapper">
        <div class="section section-fullpage">
            <div class="parallax">
                <div
                    class="parallax-background front"
                    style="background-image: url({{ url('/images/public/banners/1.jpg') }})">
                </div>
            </div>

            <div class="section-footnote">
                <div class="container text-center">
                    <h4 class="header-text">Serious, Tactical, Immersive Fun.</h4>

                    <p>
                        We pride ourselves on the absence of ranks and social drama. Our players range from former mil-sim players to beginners of Arma. Everyone is considered an equal member. No one holds the power to command anyone else outside of gameplay.<br />
                    </p>
                </div>
            </div>
        </div>

        <div class="section section-fullpage section-presentation brand-background">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center">
                        <div class="description">
                            <h4 class="header-text">Team</h4>

                            <p>
                                There is no formal ranking. You will never have to call someone 'sir' or adhere to unnecessary requirements. Respect is earned, not given.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6 text-center">
                        <div class="description">
                            <h4 class="header-text">Gameplay</h4>

                            <p>
                                We enforce first person and non-magnified/thermal optics on our servers for added immersion and to make the gameplay more challenging and fair.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 text-center">
                        <div class="description">
                            <h4 class="header-text">Missions</h4>

                            <p>
                                Play a wide range of scenarios, both cooperative and adversarial. We don't limit ourselves to a particular faction or style, each week is different in some way.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6 text-center">
                        <div class="description">
                            <h4 class="header-text">Discussion</h4>

                            <p>
                                Active discussions on our Steam group. Voice your opinions and ideas, keep up-to-date with events and collaborate with other members.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row text-center">
                    <br /><br /><br /><br />

                    <p style="font-weight: 100;font-size: 32px;line-height: 46px;">
                        Challenging Gameplay, Tactical Fun.
                    </p>

                    <br /><br /><br /><br />
                </div>
            </div>
        </div>

        <div class="section section-fullpage section-presentation section-no-padding" style="color:#000;">
            {{-- background-image: url({{ url('/images/public/banners/mars-g.jpg') }}); background-position: 0% center; --}}
            <div style="background-image: url({{ url('/images/public/mars.png') }})" id="mars-sword"></div>

            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="description">
                            <h4 class="header-text" style="font-weight: 600;opacity: .88;">ARCMF</h4>

                            <p class="text-warm">
                                Our mission framework is based on <a href="https://github.com/ferstaberinde/F3" target="_newtab">F3</a> with some extra features and overhauls to improve our experience and provide robust missions. If you're new to mission making you'll find all the help you need with our video tutorial series and welcoming members.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="description">
                            <h4 class="header-text" style="font-weight: 600;opacity: .88;">Mars</h4>

                            <p class="text-warm">
                                Mars is our total Zeus replacement providing us with a customisable editor capable of realtime mission creation and enhancement. Mars provides integrated mod support; enabling us to expand its capabilities far beyond Zeus’ limited modules.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="section section-no-padding section-fullpage brand-background">
            <div class="text-center">
                <h1 class="archub-hero-title">ARC<b>HUB</b></h1>
                <p class="archub-hero-text">Built in-house, our community management system.</p>

                <div class="archub-hero-screenshot" style="box-shadow: 0 -10px 20px rgba(0, 0, 0, 0.15);border-radius: 10px 10px 0 0;">
                    <img src="{{ url('/images/public/archub/1.png') }}">
                </div>
            </div>
        </div>

        <div class="section section-no-padding section-dark section-fullpage" {{-- style="color:#000;" --}}>
            <div class="container">
                <div class="row">
                    <div class="col-md-6 text-center">
                        <div class="description">
                            <h4 class="header-text">Weather</h4>

                            <p>
                                Get a full, automated weather report of every mission.
                                <br />
                                Know whether to pack a rain coat or flip flops.
                            </p>
                        </div>
                    </div>

                    <div class="col-md-6 text-center">
                        <div class="description">
                            <h4 class="header-text">Briefing</h4>

                            <p>
                                Read the mission briefing early to better understand<br/>the objectives and prepare you for leadership roles.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 text-center">
                        <div class="description">
                            <h4 class="header-text">Media</h4>

                            <p>
                                Share your Arma moments with the rest of the group.
                                <br />
                                Upload your photos and link your videos to a mission's gallery.
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6 text-center">
                        <div class="description">
                            <h4 class="header-text">After-Action Report</h4>

                            <p>
                                Review your experience of the mission.
                                <br />
                                Provide constructive feedback and share your war stories.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="row text-center">
                    <br /><br /><br /><br />

                    <p style="font-weight: 100;font-size: 32px;line-height: 46px;">
                        We're a group of leaders and engineers.
                    </p>
                </div>
            </div>
        </div>

        <div
            class="section section-no-padding section-fullpage"
            style="background-attachment: fixed;background-image: url({{ url('/images/public/banners/3.jpg') }})">
            <div class="info text-center">
                <h1 style="font-weight: 100;font-size: 72px;margin-bottom: 40px;">
                    Are you ready?
                </h1>
                
                <a href="{{ url('/join') }}" class="btn btn-primary btn-lg btn-fill">
                    Submit Your Application
                </a>
            </div>
        </div>
    </div>
@endsection
