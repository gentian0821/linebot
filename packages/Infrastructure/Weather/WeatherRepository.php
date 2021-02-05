<?php

namespace packages\Infrastructure\Weather;

use GuzzleHttp\Client;
use packages\Domain\Domain\Weather\WeatherRepositoryInterface;
use App\Services\MessageApiService;
use Illuminate\Support\Facades\Config;
use Carbon\Carbon;

class WeatherRepository implements WeatherRepositoryInterface
{
    private $messageApiService;

    public function __construct(MessageApiService $messageApiService)
    {
        $this->messageApiService = $messageApiService;
    }

    /**
     * @return array
     */
    public function forecasts(): array
    {
        $options = [
            'headers' => [
                'Content-Type' => 'application/json; charser=UTF-8',
            ],
        ];

        $client = new Client(['base_uri' => Config::get('const.open_weather_base_url')]);

        $weather_api = Config::get('const.open_weather_api_weather') . '?zip=112-0011,JP&units=metric&lang=ja&APPID=';

        $api_key = Config::get('const.open_weather_api_key');

        $response = $client->request('GET', $weather_api . $api_key, $options);

        return json_decode($response->getBody(), true);
    }

    public function sendMessage(array $weather_info): void
    {
        if (!$weather_info) {
            return;
        }

        $carousel_contents = [];
        $box_contents = [];
        $cnt = 0;
        foreach($weather_info['list'] as $weather) {
            $carbon = Carbon::createFromTimestamp($weather['dt']);
            $image_url = Config::get('const.base_url') . '/img/' . $this->get_icon($weather['weather'][0]['id']);

            if ($cnt <= 5) {
                $carousel_contents[] = $this->make_1day_flex_messages($weather, $carbon, $image_url);
            }

            if ($carbon->isoFormat('HH') == 12) {
                $box_contents[] = $this->make_5days_flex_messages($weather, $carbon, $image_url);
            }

            $cnt++;
        }

        $message = [
            [
                'type' => 'flex',
                'altText' => '今日の天気だよー',
                'contents' => [
                    'type' => 'carousel',
                    'contents' => $carousel_contents
                ]
            ],
            [
                'type' => 'flex',
                'altText' => '直近5日間の天気だよー',
                'contents' => [
                    'type' => 'bubble',
                    'header' => [
                        'type' => 'box',
                        'layout' => 'vertical',
                        'contents' => [
                            [
                                'type' => 'text',
                                'text' => '明日以降の天気'
                            ],
                        ]
                    ],
                    'body' => [
                        'type' => 'box',
                        'layout' => 'vertical',
                        'contents' => $box_contents,
                    ]
                ]
            ],
        ];

        $this->messageApiService->push($message, Config::get('const.calendar_send_to'));
    }

    private function make_1day_flex_messages(array $weather, Carbon $carbon, string $image_url): array
    {
        $body = '天気　　： ' . $weather['weather'][0]['description'];
        $body .= "\n気温　　： " . floor($weather['main']['temp']) . '℃';
        $body .= "\n体感温度： " . floor($weather['main']['feels_like']) . '℃';
        $body .= "\n湿度　　： " . floor($weather['main']['humidity']) . '%';
        $body .= "\n気圧　　： " . floor($weather['main']['grnd_level']) . 'hpa';
        $body .= "\n風速　　： " . floor($weather['wind']['speed']) . 'm';

        return [
            'type' => 'bubble',
            'body' => [
                'type' => 'box',
                'layout' => 'vertical',
                'contents' => [
                    [
                        'type' => 'text',
                        'text' => $carbon->isoFormat('YYYY年MM月DD日(ddd) HH:mm') . 'の天気'
                    ],
                    [
                        'type' => 'image',
                        'url' => $image_url,
                        'size' => 'full',
                        'aspectRatio' => '2:1'
                    ],
                    [
                        'type' => 'text',
                        'text' => $body,
                        'wrap' => true
                    ],
                ]
            ],
        ];
    }

    private function make_5days_flex_messages(array $weather, Carbon $carbon, string $image_url): array
    {
        $body = "気温：" . floor($weather['main']['temp']) . '℃';
        $body .= " 湿度：" . floor($weather['main']['humidity']) . '%';

        return [
            'type' => 'box',
            'layout' => 'horizontal',
            'contents' => [
                [
                    'type' => 'text',
                    'text' => $carbon->isoFormat('MM/DD(ddd)') . ' ',
                    'size' => 'xs',
                    'gravity' => 'center',
                    'flex' => 0
                ],
                [
                    'type' => 'image',
                    'url' => $image_url,
                    'size' => 'xxs',
                    'aspectMode' => 'fit',
                    'gravity' => 'center',
                    'flex' => 1
                ],
                [
                    'type' => 'text',
                    'text' => $body,
                    'size' => 'xs',
                    'gravity' => 'center',
                    'flex' => 7
                ],
                [
                    'type' => 'text',
                    'text' => $weather['weather'][0]['description'],
                    'size' => 'xs',
                    'gravity' => 'center',
                    'flex' => 0
                ]
            ],
        ];
    }

    public function get_icon(int $id): string
    {
        if ($id >= 200 && $id < 300) {
            return 'thunder.png';
        }

        if ($id >= 300 && $id < 400) {
            return 'drizzle.png';
        }

        if ($id >= 500 && $id <= 504) {
            return 'light_rain.png';
        }

        if ($id >= 511 && $id <= 531) {
            return 'rain.png';
        }

        if ($id >= 600 && $id < 700) {
            return 'snow.png';
        }

        if ($id == 800 || $id == 801) {
            return 'sunny.png';
        }

        if ($id == 802 || $id == 803) {
            return 'sunny_cloud.png';
        }

        if ($id == 804) {
            return 'cloud.png';
        }

        return '';
    }
}
