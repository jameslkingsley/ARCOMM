<?php

namespace App\Traits;

trait AppendableActions
{
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

        $appendableActions = array_merge([
            'view',
            'update',
            'delete',
            'restore',
            'forceDelete',
        ], $this->appendableActions ?: []);

        foreach ($appendableActions as $action) {
            $actions[$action] = auth()->user()->can($action, $this);
        }

        return $actions;
    }
}
