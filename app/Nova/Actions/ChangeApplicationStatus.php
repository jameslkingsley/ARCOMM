<?php

namespace App\Nova\Actions;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Actions\Action;
use Illuminate\Support\Collection;
use Laravel\Nova\Fields\ActionFields;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\ApplicationStatusChanged;

class ChangeApplicationStatus extends Action
{
    use InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $applications)
    {
        $template = EmailTemplate::find($fields->email);

        foreach ($applications as $application) {
            tap($application)
                ->update(['status' => $fields->status])
                ->notify(new ApplicationStatusChanged($application, $template));
        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        $templates = [];

        foreach (EmailTemplate::all() as $template) {
            $templates[$template->id] = $template->subject;
        }

        return [
            Select::make('Status')->options([
                'pending' => 'Pending',
                'approved' => 'Approved',
                'declined' => 'Declined',
            ]),

            Select::make('Email')->options($templates),
        ];
    }
}
