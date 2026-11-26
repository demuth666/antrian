<?php

return [

    'counter' => 'the counter',

    'status_messages' => [
        'waiting' => 'Waiting for your turn. Watch the screen and this page.',
        'called' => 'Your number has been called. Please proceed to :counter.',
        'served' => 'You are being served. If you need help, ask an officer.',
        'done' => 'Service completed. Thank you.',
        'absent' => 'You did not show up when called. Please contact an officer.',
        'cancelled' => 'The queue was cancelled. Please contact an officer.',
    ],

    'remaining' => ':count queue ahead|:count queues ahead',
    'next_in_line' => 'You are next, please get ready',
    'transition_denied' => 'Queue status cannot be changed from :from to :to.',
    'registration_log' => 'Registration created',
    'status_log' => 'Status changed to :status.',

    'show' => [
        'title' => 'Queue ticket :number',
        'description' => 'Your priority service queue number and QR code.',
        'kicker' => 'Priority service',
        'heading' => 'Queue ticket',
        'lead' => 'Keep this page or download the ticket. Show your number or QR code when your turn arrives.',
        'download' => 'Download ticket',
        'new_registration' => 'New registration',
    ],

    'ticket' => [
        'aria' => 'Your ticket',
        'qr_aria' => 'QR code for queue number :number',
        'number_label' => 'Queue number',
        'priority' => 'Priority',
        'scan' => 'Scan this code at the counter',
        'name' => 'Name',
        'service' => 'Service',
        'date' => 'Date',
    ],

    'now_serving' => [
        'aria' => 'Now serving',
        'kicker' => 'Now serving',
        'empty' => 'No counter is active yet',
        'note' => 'Updated automatically every 5 seconds',
    ],

    'check' => [
        'title' => 'Check queue',
        'description' => 'Find your queue ticket with the national ID number and phone number you used when registering.',
        'kicker' => 'Check queue',
        'heading_before' => 'Find your ',
        'heading_highlight' => 'ticket',
        'heading_after' => '.',
        'lead' => 'Enter the national ID number and phone number you used when registering. We will reopen your ticket if it is still active today.',
        'meta' => 'No account needed · Active tickets only',
        'form_label' => 'Ticket lookup',
        'stamp' => 'Ticket check',
        'preview_aria' => 'Ticket preview',
        'preview_label' => 'Your ticket',
        'preview_number_label' => 'Queue number',
        'preview_note' => 'Your number appears here once the details match.',
        'preview_status' => 'Active tickets only',
        'nik_hint' => '16 digits',
        'phone' => 'Phone number',
        'phone_hint' => 'used to register',
        'submit' => 'Check my queue',
        'help' => 'Still cannot find your ticket? Contact the :instansi officer at :telepon. Not registered yet?',
        'help_fallback' => 'Still cannot find your ticket? Ask an officer at the priority counter. Not registered yet?',
        'help_link' => 'Get a queue number',
        'new_registration' => 'New registration',
        'found' => 'Your ticket was found. Keep this page so you do not lose your ticket.',
        'not_found' => 'Data not found. Check your national ID number and phone number, then try again.',
    ],

    'rate_limit' => [
        'minutes' => ':count minute|:count minutes',
        'seconds' => ':count second|:count seconds',
        'message' => 'Too many attempts. Please try again in :time.',
    ],

    'js' => [
        'ticket_label' => 'Queue ticket',
        'number_label' => 'Queue number',
        'scan' => 'Scan this code at the counter',
        'name' => 'Name',
        'service' => 'Service',
        'date' => 'Date',
        'show' => 'Show this ticket to an officer when your number is called',
        'save' => 'Save this image as proof of your queue',
        'preparing' => 'Preparing your ticket',
        'downloaded' => 'Ticket downloaded',
        'failed' => 'Download failed',
        'filename' => 'queue-ticket',
    ],

];
