<?php

namespace packages\Domain\Domain\Notify;

use App\Services\MessageApiService;

interface NotifyRepositoryInterface
{
    public function sendMessage(MessageApiService $messageService, string $message): void;
}
