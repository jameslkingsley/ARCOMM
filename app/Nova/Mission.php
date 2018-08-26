<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\MorphMany;

class Mission extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'App\\Models\\Mission';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'name', 'ref', 'mode'
    ];

    /**
     * The relationships that should be eager loaded on index queries.
     *
     * @var array
     */
    public static $with = ['user', 'map', 'verifiedBy'];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),

            Text::make('Reference', 'ref')
                ->exceptOnForms()
                ->sortable(),

            BelongsTo::make('Creator', 'user', 'App\\Nova\\User')
                ->searchable(),

            Text::make('Name')
                ->sortable()
                ->rules('required', 'max:255'),

            Textarea::make('Summary'),

            Text::make('Mode')->sortable()
                ->updateRules('in:coop,adversarial,preop')
                ->creationRules('in:coop,adversarial,preop')
                ->displayUsing(function ($value) {
                    return studly_case($value);
                }),

            BelongsTo::make('Map', 'map', 'App\\Nova\\Map')
                ->searchable(),

            Text::make('Locked Briefings', function () {
                if (is_null($this->locked_briefings)) {
                    return null;
                }

                return strtoupper(
                    collect($this->locked_briefings)->implode(', ')
                );
            }),

            BelongsTo::make('Verified By', 'verifiedBy', 'App\\Nova\\User')
                ->searchable(),

            DateTime::make('Verified At')->hideFromIndex(),
            Text::make('Verified At', function () {
                return optional($this->verified_at)->diffForHumans();
            })->hideFromDetail(),

            DateTime::make('Created At')->hideFromIndex(),
            DateTime::make('Updated At')->hideFromIndex(),

            MorphMany::make('Comments'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    /**
     * Determine if the given resource is authorizable.
     *
     * @return bool
     */
    public static function authorizable()
    {
        return false;
    }
}
