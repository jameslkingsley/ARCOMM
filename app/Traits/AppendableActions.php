<?php

namespace App\Traits;

trait AppendableActions
{
    /**
     * The appendable actions.
     *
     * @var array
     */
    protected $appendableActions = [
        'view',
        'update',
        'delete',
        'restore',
        'forceDelete',
    ];

    /**
     * Boots the trait.
     *
     * @return void
     */
    public static function bootAppendableActions()
    {
        static::retrieved(function ($model) {
            $model->append('actions');
        });
    }

    /**
     * Gets the available actions the authenticated user can perform.
     *
     * @return array
     */
    public function getActionsAttribute()
    {
        $actions = [];

        foreach ($this->appendableActions as $action) {
            $actions[$action] = auth()->user()->can($action, $this);
        }

        return $actions;
    }
}
