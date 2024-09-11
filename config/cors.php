<?php

return [
    'allow_origins' => ['*'],
    'allow_methods' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS'],
    'allow_headers' => ['Content-Type', 'Authorization', 'X-Requested-With'],
    'expose_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
