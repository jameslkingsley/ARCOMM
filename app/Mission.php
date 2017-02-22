<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use App\Map;
use App\User;
use App\MissionComment;
use Storage;

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
            return 'https://source.unsplash.com/random/1920x1080';
        }
    }

    public function draft()
    {
        $comment = MissionComment::
            where('mission_id', $this->id)
            ->where('user_id', auth()->user()->id)
            ->first();

        return $comment;
    }
}
