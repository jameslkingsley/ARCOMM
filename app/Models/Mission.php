<?php

namespace App\Models;

use App\Traits\Commentable;
use App\Traits\BelongsToUser;
use Spatie\MediaLibrary\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Mission extends Model implements HasMedia
{
    use Commentable,
        HasMediaTrait,
        BelongsToUser;

    /**
     * Guarded attributes.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Attribute casts.
     *
     * @var array
     */
    protected $casts = [
        'locked_briefings' => 'array'
    ];

    /**
     * Attribute dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'last_played',
    ];

    /**
     * Appended attributes.
     *
     * @var array
     */
    protected $appends = [
        'banner'
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'ref';
    }

    /**
     * Registers the media collections for media library.
     *
     * @return void
     */
    public function registerMediaCollections()
    {
        $this->addMediaCollection('banner')
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->width(512)
                    ->height(512);
            });

        $this->addMediaCollection('images')
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->width(512)
                    ->height(512);
            });
    }

    /**
     * Gets the mission's banner image url.
     *
     * @return array
     */
    public function getBannerAttribute()
    {
        return [
            'full' => $this->getFirstMediaUrl('banner'),
            'thumb' => $this->getFirstMediaUrl('banner', 'thumb'),
        ];
    }

    /**
     * Gets the ext attribute.
     *
     * @return object
     */
    public function getExtAttribute($value)
    {
        return json_decode($value, false);
    }

    /**
     * Gets the cfg attribute.
     *
     * @return object
     */
    public function getCfgAttribute($value)
    {
        return json_decode($value, false);
    }

    /**
     * Gets the sqm attribute.
     *
     * @return object
     */
    public function getSqmAttribute($value)
    {
        return json_decode($value, false);
    }

    /**
     * Gets the associated map model.
     *
     * @return \App\Models\Map
     */
    public function map()
    {
        return $this->belongsTo(Map::class);
    }

    /**
     * Gets the verified by user model.
     *
     * @return \App\Models\User
     */
    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'id', 'verified_by');
    }

    /**
     * Gets the mission after-action reports.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function afterActionReports()
    {
        return $this->comments()
            ->whereCollection(null)
            ->orderBy('created_at');
    }

    /**
     * Gets the mission notes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notes()
    {
        return $this->comments()
            ->whereCollection('notes')
            ->orderBy('created_at');
    }
}
