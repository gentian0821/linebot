<?php

namespace packages\UseCase\Notify;

use packages\Infrastructure\Notify\NotifyRepositoryInterface;

class NotifyUseCase
{
    private $notifyRepository;

    public function __construct(NotifyRepositoryInterface $notifyRepository)
    {
        $this->notifyRepository = $notifyRepository;
    }

    public function send(array $params)
    {
        $this->notifyRepository->sendMessage($params);
    }
}
