<?php

namespace Tilda\Tasks;

interface TaskInterface
{
    public function getDescription(): string;
    public function execute(): void;
}