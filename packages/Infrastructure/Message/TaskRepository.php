<?php

namespace packages\Infrastructure\Message;

use App\Services\MessageApiService;
use App\Task;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository implements TaskRepositoryInterface
{
    private $messageApiService;

    public function __construct(MessageApiService $messageApiService)
    {
        $this->messageApiService = $messageApiService;
    }

    public function fetch(): Collection
    {
        return Task::where('reserved_at', date('Y-m-d H:00:00'))->get();
    }

    public function sendMessage(Collection $tasks): void
    {
        if (!$tasks) {
            return;
        }

        $message_objects = [];

        foreach ($tasks as $task) {
            $message_objects[$task->send_to][] = [
                'type' => 'text',
                'text' => $task->send_message,
            ];
        }

        foreach ($message_objects as $send_to => $messages) {
            $this->messageApiService->push($messages, $send_to);
        }
    }
}
