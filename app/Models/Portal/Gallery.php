<?php

namespace App\Models\Portal;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Gallery extends Model implements HasMedia
{
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'permalink',
        'container'
    ];

    /**
     * Media library image conversions.
     *
     * @return void
     */
    public function registerMediaConversions(): void
    {
        $this->addMediaConversion('thumb')
             ->setManipulations(['w' => 256, 'h' => 256, 'fit' => 'crop'])
             ->performOnCollections('images');
    }
}
