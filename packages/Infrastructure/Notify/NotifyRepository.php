<?php

namespace packages\Infrastructure\Notify;


use App\Services\MessageApiService;
use Illuminate\Support\Facades\Config;
use packages\Domain\Domain\Notify\NotifyRepositoryInterface;

class NotifyRepository implements NotifyRepositoryInterface
{
    public function sendMessage(MessageApiService $messageService, string $message): void
    {
        if (!$message) {
            return;
        }

        $message_objects = [];

        $message_objects[Config::get('fayc4_send_to')][] = [
            'type' => 'text',
            'text' => $message,
        ];

        foreach ($message_objects as $send_to => $messages) {
            $messageService->push($messages, $send_to);
        }
    }
}
