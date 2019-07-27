<?php

return [
    'line_access_token' => env('LINE_ACCESS_TOKEN'),

    'line_base_uri' => 'https://api.line.me',

    'line_reply_api' => '/v2/bot/message/reply',

    'line_push_api' => '/v2/bot/message/push',

    'line_content_api' => '/v2/bot/message/%s/content',

    'cloud_vision_base_url' => 'https://vision.googleapis.com',

    'cloud_vision_annotate_api' => '/v1/images:annotate',

    'cloud_vision_api_key' => env("GOOGLE_CLOUD_VISION_API_KEY"),

    'translation_api_key' => env("GOOGLE_TRANSLATION_API_KEY"),
];
