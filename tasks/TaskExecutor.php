<?php

declare(strict_types=1);

namespace Tilda\Tasks;

final class TaskExecutor
{
    public function __construct(private TaskInterface $task)
    {
    }

    public function execute(): void
    {
        echo "Task description:\n";
        echo $this->task->getDescription();
        echo "\n==========\n";

        $this->task->execute();

        echo "\n==========\n";
    }
}