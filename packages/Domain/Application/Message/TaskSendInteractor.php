<?php

namespace packages\Domain\Application\Message;

use packages\Infrastructure\Message\TaskRepositoryInterface;

class TaskSendInteractor
{
    private $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function handle()
    {
        $events = $this->taskRepository->fetch();

        $this->taskRepository->sendMessage($events);
    }
}
