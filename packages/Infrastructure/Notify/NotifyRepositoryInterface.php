<?php

namespace packages\Infrastructure\Notify;

interface NotifyRepositoryInterface
{
    public function sendMessage(array $params): void;
}
