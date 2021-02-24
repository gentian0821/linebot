<?php

namespace packages\Domain\Application\Notify;

use packages\UseCase\Notify\NotifyUseCase;

class NotifySendInteractor
{
    private $notifyUseCase;

    public function __construct(NotifyUseCase $notifyUseCase)
    {
        $this->notifyUseCase = $notifyUseCase;
    }

    public function handle(array $params)
    {
        $this->notifyUseCase->send($params);
    }
}
