<?php
/**
 * Jingga
 *
 * PHP Version 8.2
 *
 * @package   phpOMS\DataStorage\Database\Query\Grammar
 * @copyright Dennis Eichhorn
 * @license   OMS License 2.2
 * @version   1.0.0
 * @link      https://jingga.app
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Query\Grammar;

use phpOMS\DataStorage\Database\Query\Builder;

/**
 * Grammar class.
 *
 * @package phpOMS\DataStorage\Database\Query\Grammar
 * @license OMS License 2.2
 * @link    https://jingga.app
 * @since   1.0.0
 */
class PostgresGrammar extends Grammar
{
    /**
     * Compile random.
     *
     * @param Builder $query   Builder
     * @param array   $columns Columns
     *
     * @return string
     *
     * @since 1.0.0
     */
    protected function compileRandom(Builder $query, array $columns) : string
    {
        $expression = $this->expressionizeTableColumn($columns);

        if ($expression === '') {
            $expression = '*';
        }

        return 'SELECT ' . $expression . ' ' . $this->compileFrom($query, $query->from) . ' ORDER BY RANDOM() ' . $this->compileLimit($query, $query->limit ?? 1);
    }
}
