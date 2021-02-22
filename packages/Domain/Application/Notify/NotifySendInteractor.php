<?php

namespace packages\Domain\Application\Notify;

use App\Services\MessageApiService;
use packages\Domain\Domain\Notify\NotifyRepositoryInterface;
use packages\UseCase\Notify\Send\NotifySendUseCaseInterface;


class NotifySendInteractor implements NotifySendUseCaseInterface
{
    private $notifyRepository;

    public function __construct(NotifyRepositoryInterface $notifyRepository)
    {
        $this->notifyRepository = $notifyRepository;
    }

    public function handle(array $params)
    {
        $this->notifyRepository->sendMessage(new MessageApiService(), $params);
    }
}
