<?php

namespace packages\Infrastructure\Message;


use App\Services\MessageApiService;
use App\Task;
use Illuminate\Database\Eloquent\Collection;
use packages\Domain\Domain\Message\TaskRepositoryInterface;

class TaskRepository implements TaskRepositoryInterface
{
    /**
     * @return Collection
     */
    public function fetch(): Collection
    {
        return Task::where('reserved_at', date('Y-m-d H:00:00'))->get();
    }

    /**
     * @param MessageApiService $messageService
     * @param Collection $tasks
     */
    public function sendMessage(MessageApiService $messageService, Collection $tasks): void
    {
        $message_objects = [];
        foreach ($tasks as $task) {
            $message_objects[$task->send_to][] = [
                'type' => 'text',
                'text' => $task->send_message,
            ];
        }

        foreach ($message_objects as $send_to => $messages) {
            $messageService->push($messages, $send_to);
        }
    }
}
