<?php

namespace packages\Infrastructure\Notify;


use App\Services\MessageApiService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use packages\Domain\Domain\Notify\NotifyRepositoryInterface;

class NotifyRepository implements NotifyRepositoryInterface
{
    public function sendMessage(MessageApiService $messageService, string $message): void
    {
        if (!$message) {
            return;
        }

        Log::info(print_r(Config::get('fayc4_send_to'), true));
        Log::info(print_r($message, true));
        $message_objects = [];

        $message_objects[Config::get('fayc4_send_to')][] = [
            'type' => 'text',
            'text' => $message,
        ];

        foreach ($message_objects as $send_to => $messages) {
            Log::info(print_r($send_to, true));
            Log::info(print_r($messages, true));
            $messageService->push($messages, $send_to);
        }
    }
}
