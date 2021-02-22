<?php

return [
    'base_url' => 'https://linebot-fayc4.herokuapp.com',

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

    'test_send_to' => env("TEST_SEND_TO"),

    'fayc4_send_to' => env('FAYC4_SEND_TO'),

    'open_weather_base_url' => 'https://api.openweathermap.org',

    'open_weather_image_url' => 'https://openweathermap.org/img/w/',

    'open_weather_api_weather' => '/data/2.5/forecast',

    'open_weather_api_key' => env("OPEN_WEATHER_API_KEY"),
];
