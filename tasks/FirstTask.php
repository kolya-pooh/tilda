<?php

declare(strict_types=1);

namespace Tilda\Tasks;

final class FirstTask implements TaskInterface
{
    private string $description = '
        Нужно вывести лесенкой числа от 1 до 100.
        1
        2 3
        4 5 6
        ...
    ';
    private const int FROM = 1;
    private const int TO = 100;

    public function getDescription(): string
    {
        return $this->description;
    }

    public function execute(): void
    {
        $cur = self::FROM;
        $count = $countFrom = 1;

        while ($cur <= self::TO) {
            echo $cur . " ";
            $count--;
            $cur++;

            if ($count === 0) {
                echo "\n";
                $countFrom++;
                $count = $countFrom;
            }
        }
    }
}