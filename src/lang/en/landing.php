<?php

return [

    'title' => 'SIAP RENTAN MPP SUMEDANG | Priority Service, Equality for All',
    'description' => 'Priority Queue Information System for Vulnerable Groups at the Sumedang Regency Public Service Mall. Priority queue registration for people with disabilities, older adults, pregnant women, and vulnerable residents without creating an account.',

    'nav' => [
        'services' => 'Services',
        'audience' => 'Who it is for',
        'steps' => 'How to register',
        'check' => 'Check queue',
        'faq' => 'FAQ',
    ],

    'cta' => [
        'register' => 'Register online',
        'register_short' => 'Register',
        'check' => 'Check my queue',
        'view_steps' => 'See how to register',
    ],

    'hero' => [
        'kicker' => 'Priority queue, no account needed',
        'title_before' => 'SIAP RENTAN',
        'title_highlight' => 'MPP SUMEDANG',
        'title_after' => '',
        'long_name' => 'Priority Queue Information System for Vulnerable Groups at the Sumedang Regency Public Service Mall.',
        'tagline' => 'Priority Service, Equality for All.',
        'lead' => 'For people with disabilities, older adults, pregnant women, and vulnerable residents. Register from home, get a queue number, and come when your turn is close.',
        'help' => 'Need assistance from the entrance? Ask an officer, we are ready to help.',
        'ticket' => [
            'aria' => 'Example priority queue ticket',
            'caption' => 'Example priority queue ticket number A-014 for ID card registration at Priority counter 02.',
            'priority' => 'Priority',
            'number_label' => 'Queue number',
            'name' => 'Name',
            'name_value' => 'Siti R.',
            'service' => 'Service',
            'service_value' => 'ID card registration',
            'counter' => 'Counter',
            'counter_value' => 'Priority 02',
            'date' => 'Date',
            'scan' => 'Scan the code at the counter',
            'next' => 'You are next',
        ],
    ],

    'features' => [
        'assistance_title' => 'Assistance is prepared',
        'assistance_body' => 'Tell us what you need when you register. Officers prepare it before you arrive.',
        'queue_title' => 'Track from your phone',
        'queue_body' => 'Get a queue number and ticket code, then track your turn from your phone.',
        'representative_title' => 'Someone can register for you',
        'representative_body' => 'A family member or companion can fill in the form for you.',
    ],

    'service' => [
        'kicker' => 'Our service',
        'heading' => 'Counters, waiting areas, and companion officers',
        'lead' => 'We prepare dedicated counters, waiting areas, and companion officers so every resident feels comfortable. Just come in, and we will help with the rest.',
        'board' => [
            'aria' => 'Example counter screen now serving A-013 at the priority counter.',
            'label' => 'Counter screen',
            'now' => 'Now serving',
            'counter' => 'Counter 02 · Priority',
            'sample' => 'Sample counter screen',
            'rows' => [
                ['counter' => 'Counter 01', 'name' => 'Registration', 'number' => 'A-011', 'now' => false],
                ['counter' => 'Counter 02', 'name' => 'Priority', 'number' => 'A-013', 'now' => true],
                ['counter' => 'Counter 03', 'name' => 'Information', 'number' => 'B-004', 'now' => false],
            ],
        ],
    ],

    'audience' => [
        'kicker' => 'Who it is for',
        'heading' => 'Who is served on the priority lane',
        'disability_title' => 'People with disabilities',
        'disability_body' => 'Physical, visual, hearing or speech, intellectual, mental health, and multiple disabilities.',
        'elderly_title' => 'Older adults',
        'elderly_body' => 'Residents aged 60 and above.',
        'pregnancy_title' => 'Pregnant women and toddlers',
        'pregnancy_body' => 'Pregnant women or anyone bringing a toddler.',
        'vulnerable_title' => 'Other vulnerable residents',
        'vulnerable_body' => 'Residents who are seriously ill, disaster survivors, or in other special circumstances.',
    ],

    'steps' => [
        'kicker' => 'How to register',
        'heading' => 'Register, choose assistance, then come',
        'register_title' => 'Register',
        'register_body' => 'Fill in the online form in about 3 minutes, call us, or register directly at the priority counter. No account needed.',
        'assistance_title' => 'Choose assistance',
        'assistance_body' => 'Tell us what assistance you need, such as a wheelchair, sign language interpreter, or companion officer. We will prepare it.',
        'come_title' => 'Come and show your ticket code',
        'come_body' => 'Come when your number is almost called. Bring your ID, then show the ticket code to an officer. We will welcome you and explain the next steps.',
    ],

    'faq' => [
        'kicker' => 'FAQ',
        'heading' => 'Frequently asked questions',
        'items' => [
            [
                'question' => 'What should I prepare?',
                'answer' => 'Your national ID (KTP), an active phone number, and the service you need. If you come with a companion, have their name ready.',
            ],
            [
                'question' => 'Do I need to bring proof of disability?',
                'answer' => 'No. The form only asks for your category and the assistance you need.',
            ],
            [
                'question' => 'What if I cannot fill in the form myself?',
                'answer' => 'A family member or companion can fill it in. You can also come directly to the priority counter, and an officer will help.',
            ],
            [
                'question' => 'What if I am late?',
                'answer' => 'Come to the priority counter and ask an officer. They will help arrange your turn.',
            ],
            [
                'question' => 'Is my data safe?',
                'answer' => 'Your data is only used for service purposes and can only be seen by authorized officers.',
            ],
        ],
    ],

    'final' => [
        'title' => 'Get your queue number',
        'body' => 'About 3 minutes. Your number and ticket code appear right away.',
        'button' => 'Register online',
        'stub_label' => 'No account',
        'stub_value' => '3 minutes',
    ],

    'banner' => [
        'active' => 'You have an active queue',
        'separator' => ',',
        'link' => 'view ticket',
    ],

];
