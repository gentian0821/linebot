<?php

namespace packages\UseCase\Notify\Send;

interface NotifySendUseCaseInterface
{
    public function handle(string $message);
}
