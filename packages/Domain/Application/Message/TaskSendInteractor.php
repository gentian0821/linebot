<?php

namespace packages\Domain\Application\Message;

use packages\UseCase\Task\TaskUseCase;

class TaskSendInteractor
{
    private $taskUseCase;

    public function __construct(TaskUseCase $taskUseCase)
    {
        $this->taskUseCase = $taskUseCase;
    }

    public function handle(): void
    {
        $this->taskUseCase->send();
    }
}
