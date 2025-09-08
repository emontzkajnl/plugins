<?php

declare(strict_types=1);

namespace ACP\ConditionalFormat\Formatter\Media;

use AC\Column;
use ACP\ConditionalFormat\Formatter;
use ACP\Expression\ComparisonOperators;

class FileSizeFormatter implements Formatter
{

    public function get_type(): string
    {
        return self::INTEGER;
    }

    public function format(string $value, $id, Column $column, string $operator_group): string
    {
        if (ComparisonOperators::class === $operator_group) {
            $float = (float)$value;

            return (string)$float;
        }

        return $value;
    }
}