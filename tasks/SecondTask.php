<?php

declare(strict_types=1);

namespace Tilda\Tasks;

final class SecondTask implements TaskInterface
{
    private string $description = '
        Нужно заполнить массив 5 на 7 случайными уникальными числами от 1 до 1000.
        Вывести получившийся массив и суммы по строкам и по столбцам.
    ';

    private const int HEIGHT = 5;
    private const int WIDTH = 7;
    private const int RANGE_FROM = 1;
    private const int RANGE_TO = 1000;
    private const int MAX_RAND_SECONDS = 10;

    private array $matrix = [];
    private array $uniqPool = [];

    public function getDescription(): string
    {
        return $this->description;
    }

    public function execute(): void
    {
        $cellSize = strlen((string) self::RANGE_TO) + 1;
        $columnsSumCounter = [];

        for ($i=0; $i<self::HEIGHT; $i++) {
            $sum = 0;
            for ($j=0; $j<self::WIDTH; $j++) {
                $cur = $this->getUniqueInt();
                $this->matrix[$i][$j] = $cur;
                echo str_pad((string) $cur, $cellSize, " ", STR_PAD_LEFT);
                $sum += $cur;

                $columnsSumCounter[$j] = ($columnsSumCounter[$j] ?? 0) + $cur;
            }
            echo " | {$sum}\n";
        }

        echo str_pad('', $cellSize * self::WIDTH + 1, "_");
        echo "|\n";

        foreach ($columnsSumCounter as $columnSum) {
            echo str_pad((string) $columnSum, $cellSize, " ", STR_PAD_LEFT);
        }
    }

    private function getUniqueInt(): int
    {
        $start = microtime(true);
        while (true) {
            $cur = mt_rand(self::RANGE_FROM, self::RANGE_TO);
            if (!isset($this->uniqPool[$cur])) {
                $this->uniqPool[$cur] = true;

                return $cur;
            }
            if (microtime(true) - $start > self::MAX_RAND_SECONDS) {
                throw new \RuntimeException('Could not find unique number after '. self::MAX_RAND_SECONDS.' seconds');
            }
        }
    }

}