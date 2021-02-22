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

        $messageService->push(
            [
                'type' => 'text',
                'text' => $message,
            ],
            Config::get('fayc4_send_to')
        );
    }
}
