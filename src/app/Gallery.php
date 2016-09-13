<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMediaConversions;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Gallery extends Model implements HasMediaConversions
{
    use HasMediaTrait;

    protected $fillable = [
        'name',
        'permalink',
        'container'
    ];

    public function registerMediaConversions()
    {
        $this->addMediaConversion('thumb')
             ->setManipulations(['w' => 256, 'h' => 256, 'fit' => 'crop'])
             ->performOnCollections('images');
    }
}
