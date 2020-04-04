<?php

namespace packages\Domain\Application\Message;

use App\Services\MessageApiService;
use packages\Domain\Domain\Message\TaskRepositoryInterface;
use packages\UseCase\Task\Send\TaskSendUseCaseInterface;


class TaskSendInteractor implements TaskSendUseCaseInterface
{
    /**
     * @var TaskRepositoryInterface
     */
    private $taskRepository;

    /**
     * TaskSendInteractor constructor.
     * @param TaskRepositoryInterface $taskRepository
     */
    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * @throws \Google_Exception
     */
    public function handle()
    {
        $events = $this->taskRepository->fetch();

        $this->taskRepository->sendMessage(new MessageApiService(), $events);
    }
}
