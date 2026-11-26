<?php

return [

    'nav' => [
        'today' => 'Today\'s queue',
        'find_ticket' => 'Find ticket',
    ],

    'dashboard' => [
        'title' => 'Today\'s queue',
        'kicker' => 'Officer panel',
        'call_next' => 'Call next',
        'find_ticket' => 'Find a resident\'s ticket',
        'no_counter' => 'Your account is not assigned to a counter yet. Contact the admin so you can start serving the queue.',
        'no_services' => ':counter has no services yet. Contact the admin to set up the counter\'s services.',
        'total_today' => '{0} :total queue|{1} :total queue|[2,*] :total queues',
        'active_title' => 'At the counter',
        'waiting_title' => 'Waiting',
        'closed_title' => 'Closed',
        'arrival_order' => 'Arrival order',
        'empty_desk' => 'No one is at the counter yet',
        'empty_page_title' => 'The counter is still empty',
        'empty_page_body' => 'Resident queues will appear here once they register.',
        'waiting_empty' => 'No one is waiting',
        'closed_empty' => 'No queues closed today',
        'table_aria' => 'Waiting queue list',
        'headers' => [
            'queue' => 'Queue',
            'resident' => 'Resident',
            'since' => 'Since',
            'actions' => 'Actions',
        ],
        'needs' => 'Needs',
        'needs_none' => 'None',
        'companion' => 'Companion',
        'called_at' => 'Called :time',
        'serving_since' => 'Serving since :time',
        'start' => 'Start serving',
        'absent' => 'No-show',
        'finish' => 'Finish',
        'cancel' => 'Cancel',
    ],

    'find' => [
        'title' => 'Find ticket',
        'kicker' => 'Officer panel',
        'heading' => 'Find a resident\'s ticket',
        'description' => 'Use the national ID number to find today\'s queue when a resident loses their ticket.',
        'back' => 'Today\'s queue',
        'nik_hint' => '16 digits',
        'note' => 'The national ID number is not shown again on this page.',
        'submit' => 'Find ticket',
        'result_title' => 'Search results',
        'empty' => 'There are no queues for that national ID number today. Make sure the number is correct.',
        'table_aria' => 'Ticket search results',
        'headers' => [
            'queue' => 'Queue',
            'service' => 'Service',
            'status' => 'Status',
            'actions' => 'Actions',
        ],
        'open' => 'Open ticket',
    ],

    'flash' => [
        'no_queue' => 'There are no waiting queues.',
        'no_counter' => 'Your account is not assigned to a counter.',
        'no_counter_services' => 'The officer account is not assigned to a counter.',
        'called' => ':number called to :counter.',
        'started' => 'started being served.',
        'finished' => 'has finished being served.',
        'absent' => 'was marked as a no-show.',
        'cancelled' => 'was cancelled.',
    ],

];
