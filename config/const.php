<?php

return [
    'line_access_token' => env('LINE_ACCESS_TOKEN'),

    'line_base_uri' => 'https://api.line.me',

    'line_reply_api' => '/v2/bot/message/reply',

    'line_push_api' => '/v2/bot/message/push',

    'line_content_api' => '/v2/bot/message/%s/content',

    'cloud_vision_base_url' => 'https://vision.googleapis.com',

    'cloud_vision_annotate_api' => '/v1/images:annotate',

    'google_api_key' => env("GOOGLE_API_KEY"),

    'google_api_credential' => env("GOOGLE_API_CREDENTIAL"),

    'google_application_credential' => env('GOOGLE_APPLICATION_CREDENTIALS'),

    'calendar_id' => env("GOOGLE_CALENDAR_ID"),

    'calendar_send_to' => env("GOOGLE_CALENDAR_SEND_TO"),
];
