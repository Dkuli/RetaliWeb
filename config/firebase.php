<?php
return [
    'credentials' => [
        'file' => storage_path('app/firebase/google-services.json'),
    ],
    'database' => [
        'url' => env('FIREBASE_DATABASE_URL'),
    ],
];
