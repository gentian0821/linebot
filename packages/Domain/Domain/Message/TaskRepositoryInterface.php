<?php

namespace packages\Domain\Domain\Message;

use Google_Service_Calendar;
use App\Services\MessageApiService;
use Illuminate\Database\Eloquent\Collection;

interface TaskRepositoryInterface
{
    /**
     * @return Collection
     */
    public function fetch(): Collection;

    /**
     * @param MessageApiService $messageService
     * @param Collection $tasks
     * @return void
     */
    public function sendMessage(MessageApiService $messageService, Collection $tasks): void;
}
