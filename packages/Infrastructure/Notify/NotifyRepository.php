<?php

namespace packages\Infrastructure\Notify;

use App\Services\MessageApiService;
use Illuminate\Support\Facades\Config;

class NotifyRepository implements NotifyRepositoryInterface
{
    private $messageApiService;

    public function __construct(MessageApiService $messageApiService)
    {
        $this->messageApiService = $messageApiService;
    }

    public function sendMessage(array $params): void
    {
        if (!$params) {
            return;
        }

        $message = "From: " . $params['from'] . "\nSubject: " . $params['subject'] .
            "\n\n" . explode('-------', $params['message'])[0];

        $this->messageApiService->push(
            [['type' => 'text', 'text' => $message]],
            Config::get('const.fayc4_send_to')
        );
    }
}
