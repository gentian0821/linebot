<?php

namespace packages\UseCase\Task;

use packages\Infrastructure\Message\TaskRepositoryInterface;

class TaskUseCase
{
    private $taskRepository;

    public function __construct(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    public function send(): void
    {
        $events = $this->taskRepository->fetch();

        $this->taskRepository->sendMessage($events);
    }
}
