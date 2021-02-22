<?php

namespace packages\Domain\Application\Notify;

use packages\Infrastructure\Notify\NotifyRepositoryInterface;

class NotifySendInteractor
{
    private $notifyRepository;

    public function __construct(NotifyRepositoryInterface $notifyRepository)
    {
        $this->notifyRepository = $notifyRepository;
    }

    public function handle(array $params)
    {
        $this->notifyRepository->sendMessage($params);
    }
}
