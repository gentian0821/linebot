<?php

namespace packages\Infrastructure\Notify;


use App\Services\MessageApiService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use packages\Domain\Domain\Notify\NotifyRepositoryInterface;

class NotifyRepository implements NotifyRepositoryInterface
{
    public function sendMessage(MessageApiService $messageService, array $params): void
    {
        if (!$params) {
            return;
        }

        $message = "From: " . $params['from'] . "\nSubject: " . $params['subject'] .
            "\n\n" . explode('-------', $params['message'])[0];


        $messageService->push([
                'type' => 'text',
                'text' => $message,
            ],
            Config::get('const.fayc4_send_to')
        );
    }
}
