<?php

return [
    // Pools are what you reference on the front-end
    // They contain the model that will be mentioned
    'pools' => [
        'users' => [
            // Model that will be mentioned
            'model' => 'App\Models\Portal\User',

            // The column that will be used to search the model
            'column' => 'username',

            // Notification class to use when this model is mentioned
            'notification' => 'App\Notifications\MentionedInComment',

            // Automatically notify upon mentions
            'auto_notify' => true
        ],

        'missions' => [
            // Model that will be mentioned
            'model' => 'App\Models\Missions\Mission',

            // The column that will be used to search the model
            'column' => 'display_name',

            // Notification class to use when this model is mentioned
            'notification' => 'App\Notifications\MentionedInComment',

            // Automatically notify upon mentions
            'auto_notify' => false
        ]
    ]
];
