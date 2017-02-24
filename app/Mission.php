<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use App\Map;
use App\User;
use App\MissionComment;
use Storage;
use Log;
use Carbon\Carbon;

class Mission extends Model implements HasMediaConversions
{
    use HasMediaTrait;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'last_played'
    ];

    public function registerMediaConversions()
    {
        $this->addMediaConversion('thumb')
            ->setManipulations(['w' => 384, 'h' => 384, 'fit' => 'crop'])
            ->performOnCollections('images');
    }

    public function map()
    {
        return $this->belongsTo('App\Map');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function comments()
    {
        return $this->hasMany('App\MissionComment');
    }

    public function banner()
    {
        $media = $this->getMedia();

        if (count($media) > 0) {
            return $media[0]->getUrl();
        } else {
            return 'https://source.unsplash.com/category/nature';
        }
    }

    public function draft()
    {
        $comment = MissionComment::
            where('mission_id', $this->id)
            ->where('user_id', auth()->user()->id)
            ->where('published', false)
            ->first();

        return $comment;
    }

    /**
     * Unpacks the mission PBO and returns the absolute path of the folder.
     *
     * @return string
     */
    public function unpack()
    {
        $unpacked = storage_path(
            'app/missions/' .
            $this->user_id .
            '/' .
            $this->id .
            '_unpacked'
        );

        shell_exec(
            resource_path('utils/armake.exe') .
            ' unpack -f ' .
            storage_path('app/' . $this->pbo_path) .
            ' ' .
            $unpacked
        );

        // TODO Debinarize SQM

        return $unpacked;
    }

    /**
     * Deletes the unpacked mission directory.
     *
     * @return string
     */
    public function deleteUnpacked()
    {
        Storage::deleteDirectory('missions/' . $this->user_id . '/' . $this->id . '_unpacked');
    }

    public function ext()
    {
        return request()->session()->get('mission_ext');
    }

    public function sqm()
    {
        return request()->session()->get('mission_sqm');
    }

    public function config()
    {
        return request()->session()->get('mission_config');
    }

    public function storeConfigs()
    {
        $unpacked = $this->unpack();

        request()->session()->put('mission_ext', ArmaLexer::convert($unpacked . '/description.ext'));
        request()->session()->put('mission_sqm', ArmaLexer::convert($unpacked . '/mission.sqm'));
        request()->session()->put('mission_config', ArmaLexer::convert($unpacked . '/config.hpp'));
        request()->session()->put('mission_version', file_get_contents($unpacked . '/version.txt'));

        $this->deleteUnpacked();
    }

    public function version()
    {
        return request()->session()->get('mission_version');
    }

    protected static function computeLessThan($value, $options)
    {
        foreach ($options as $text => $level) {
            if ($value <= $level) {
                return $text;
            }
        }
    }

    public function fog()
    {
        return static::computeLessThan(
            (property_exists($this->sqm()->Mission->Intel, 'startFog')) ? $this->sqm()->Mission->Intel->startFog : 0,
            [
                '' => 0.0,
                'Light Fog' => 0.1,
                'Medium Fog' => 0.3,
                'Heavy Fog' => 0.5,
                'Extreme Fog' => 1.0
            ]
        );
    }

    public function overcast()
    {
        return static::computeLessThan(
            $this->sqm()->Mission->Intel->startWeather,
            [
                'Clear Skies' => 0.1,
                'Partly Cloudy' => 0.3,
                'Heavy Clouds' => 0.6,
                'Stormy' => 1.0
            ]
        );
    }

    public function rain()
    {
        $startRain = (property_exists($this->sqm()->Mission->Intel, 'startRain')) ? $this->sqm()->Mission->Intel->startRain : 0;
        $forecastRain = (property_exists($this->sqm()->Mission->Intel, 'forecastRain')) ? $this->sqm()->Mission->Intel->forecastRain : 0;
        $diff = $forecastRain - $startRain;

        return static::computeLessThan(
            $diff,
            [
                '' => 0,
                'Slight Drizzle' => 0.2,
                'Drizzle' => 0.4,
                'Rain' => 0.6,
                'Showers' => 1
            ]
        );
    }

    public function weather()
    {
        return $this->overcast() . (($this->fog() == '') ? '' : ', ' . $this->fog()) . (($this->rain() == '') ? '' : ', ' . $this->rain());
    }

    public function weatherImage()
    {
        return url('/images/weather/' . ([
            'Clear Skies' => 'clear',
            'Partly Cloudy' => 'partly sunny',
            'Heavy Clouds' => 'partly cloudy',
            'Stormy' => 'cloudy',
            'Clear Skies, Slight Drizzle' => 'slight drizzle',
            'Clear Skies, Drizzle' => 'light rain',
            'Clear Skies, Rain' => 'rain',
            'Clear Skies, Showers' => 'showers',
            'Partly Cloudy, Slight Drizzle' => 'slight drizzle',
            'Partly Cloudy, Drizzle' => 'light rain',
            'Partly Cloudy, Rain' => 'rain',
            'Partly Cloudy, Showers' => 'showers',
            'Heavy Clouds, Slight Drizzle' => 'slight drizzle',
            'Heavy Clouds, Drizzle' => 'light rain',
            'Heavy Clouds, Rain' => 'rain',
            'Heavy Clouds, Showers' => 'showers',
            'Stormy, Slight Drizzle' => 'slight drizzle',
            'Stormy, Drizzle' => 'light rain',
            'Stormy, Rain' => 'rain',
            'Stormy, Showers' => 'showers'
        ])[$this->overcast() . (($this->rain() == '') ? '' : ', ' . $this->rain())] . '.png');
    }

    public function date()
    {
        $date = Carbon::createFromDate(
            $this->sqm()->Mission->Intel->year,
            $this->sqm()->Mission->Intel->month,
            $this->sqm()->Mission->Intel->day
        );

        return $date->format('jS M Y');
    }

    public function time()
    {
        $time = Carbon::createFromTime(
            (property_exists($this->sqm()->Mission->Intel, 'hour')) ? $this->sqm()->Mission->Intel->hour : 0,
            (property_exists($this->sqm()->Mission->Intel, 'minute')) ? $this->sqm()->Mission->Intel->minute : 0,
            0
        );

        return $time->format('H:i');
    }
}
