<?php

namespace packages\Infrastructure\Message;

use Illuminate\Database\Eloquent\Collection;

interface TaskRepositoryInterface
{
    public function fetch(): Collection;

    public function sendMessage(Collection $tasks): void;
}
