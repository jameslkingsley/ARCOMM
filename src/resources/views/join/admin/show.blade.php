<h1 class="jr-name">
    {{ $jr->name }}
    <span class="{{ strtolower($jr->getStatusAsText()) }}">{{ $jr->getStatusAsText() }}</span>
</h1>

<div class="row">
    <div class="col-md-6">
        <i class="jr-icon fa fa-envelope"></i>
        <span class="jr-attr">{{ $jr->email }}</span>
    </div>
    <div class="col-md-6">
        <i class="jr-icon fa fa-calendar"></i>
        <span class="jr-attr">{{ $jr->age }}</span>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <i class="jr-icon fa fa-map-marker"></i>
        <span class="jr-attr">{{ $jr->location }}</span>
    </div>
    <div class="col-md-6">
        <i class="jr-icon fa fa-steam-square"></i>
        <span class="jr-attr"><a href="{{ $jr->steam }}" target="_newtab">Steam Account</a></span>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <i class="jr-icon fa fa-clock-o"></i>
        <span class="jr-attr">{{ ($jr->available) ? 'Available ' : 'Unavailable ' }} {{ env('SITE_OP_DAY') }}</span>
    </div>
    <div class="col-md-6">
        <i class="jr-icon fa fa-users"></i>
        <span class="jr-attr">{{ ($jr->groups) ? 'Other groups' : 'No other groups' }}</span>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <i class="jr-icon fa fa-gift"></i>
        <span class="jr-attr">{{ ($jr->apex) ? 'Owns Apex' : 'Does not own Apex' }}</span>
    </div>
</div>

<h3>Arma Experience</h3>
<p>{{ $jr->experience }}</p>

<h3>About</h3>
<p>{{ $jr->bio }}</p>
