<?php

return [
    'line_access_token' => env('LINE_ACCESS_TOKEN'),

    'line_base_uri' => 'https://api.line.me',

    'line_reply_api' => '/v2/bot/message/reply',

    'line_push_api' => '/v2/bot/message/push',

    'line_content_api' => '/v2/bot/message/%s/content',

    'translation_api_key' => env("GOOGLE_TRANSLATION_API_KEY"),

    'cloud_vision_api_key' => env("GOOGLE_CLOUD_VISION_API_KEY"),
];
