<?php
/**
 * Orange Management
 *
 * PHP Version 7.2
 *
 * @package    phpOMS\DataStorage\Database\Query
 * @copyright  Dennis Eichhorn
 * @license    OMS License 1.0
 * @version    1.0.0
 * @link       http://website.orange-management.de
 */
declare(strict_types=1);

namespace phpOMS\DataStorage\Database\Query;

use phpOMS\DataStorage\Database\BuilderAbstract;
use phpOMS\DataStorage\Database\Connection\ConnectionAbstract;

/**
 * Database query builder.
 *
 * @package    phpOMS\DataStorage\Database\Query
 * @license    OMS License 1.0
 * @link       http://website.orange-management.de
 * @since      1.0.0
 */
final class Builder extends BuilderAbstract
{
    /**
     * Is read only.
     *
     * @var bool
     * @since 1.0.0
     */
    private $isReadOnly = false;

    /**
     * Columns.
     *
     * @var array
     * @since 1.0.0
     */
    public $selects = [];

    /**
     * Columns.
     *
     * @var array
     * @since 1.0.0
     */
    public $updates = [];

    /**
     * Stupid work around because value needs to be not null for it to work in Grammar.
     *
     * @var array
     * @since 1.0.0
     */
    public $deletes = [1];

    /**
     * Into.
     *
     * @var string|\Closure
     * @since 1.0.0
     */
    public $into = null;

    /**
     * Into columns.
     *
     * @var array
     * @since 1.0.0
     */
    public $inserts = [];

    /**
     * Into columns.
     *
     * @var array
     * @since 1.0.0
     */
    public $values = [];

    /**
     * Into columns.
     *
     * @var array
     * @since 1.0.0
     */
    public $sets = [];

    /**
     * Distinct.
     *
     * @var bool
     * @since 1.0.0
     */
    public $distinct = false;

    /**
     * From.
     *
     * @var array
     * @since 1.0.0
     */
    public $from = [];

    /**
     * Joins.
     *
     * @var array
     * @since 1.0.0
     */
    public $joins = [];

    /**
     * Where.
     *
     * @var array
     * @since 1.0.0
     */
    public $wheres = [];

    /**
     * Group.
     *
     * @var array
     * @since 1.0.0
     */
    public $groups = [];

    /**
     * Order.
     *
     * @var array
     * @since 1.0.0
     */
    public $orders = [];

    /**
     * Limit.
     *
     * @var int
     * @since 1.0.0
     */
    public $limit = null;

    /**
     * Offset.
     *
     * @var int
     * @since 1.0.0
     */
    public $offset = null;

    /**
     * Binds.
     *
     * @var array
     * @since 1.0.0
     */
    private $binds = [];

    /**
     * Union.
     *
     * @var array
     * @since 1.0.0
     */
    public $unions = [];

    /**
     * Lock.
     *
     * @var bool
     * @since 1.0.0
     */
    public $lock = false;

    /**
     * Comparison OPERATORS.
     *
     * @var string[]
     * @since 1.0.0
     */
    public const OPERATORS = [
        '=',
        '<',
        '>',
        '<=',
        '>=',
        '<>',
        '!=',
        'like',
        'like binary',
        'not like',
        'between',
        'ilike',
        '&',
        '|',
        '^',
        '<<',
        '>>',
        'rlike',
        'regexp',
        'not regexp',
        '~',
        '~*',
        '!~',
        '!~*',
        'similar to',
        'not similar to',
        'in',
    ];

    /**
     * Constructor.
     *
     * @param ConnectionAbstract $connection Database connection
     * @param bool               $readOnly   Query is read only
     *
     * @since  1.0.0
     */
    public function __construct(ConnectionAbstract $connection, bool $readOnly = false)
    {
        $this->isReadOnly = $readOnly;
        $this->setConnection($connection);
    }

    /**
     * Set connection for grammar.
     *
     * @param ConnectionAbstract $connection Database connection
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function setConnection(ConnectionAbstract $connection) : void
    {
        $this->connection = $connection;
        $this->grammar    = $connection->getGrammar();
    }

    /**
     * Select.
     *
     * @param array ...$columns Columns
     *
     * @return Builder
     *
     * @todo   Closure is not working this way, needs to be evaluated befor assigning
     *
     * @since  1.0.0
     */
    public function select(...$columns) : Builder
    {
        $this->type = QueryType::SELECT;

        foreach ($columns as $key => $column) {
            if (\is_string($column) || $column instanceof \Closure) {
                $this->selects[] = $column;
            } else {
                throw new \InvalidArgumentException();
            }
        }

        return $this;
    }

    /**
     * Select.
     *
     * @param array ...$columns Columns
     *
     * @return Builder
     *
     * @todo   Closure is not working this way, needs to be evaluated befor assigning
     *
     * @since  1.0.0
     */
    public function random(...$columns) : Builder
    {
        $this->select(...$columns);

        $this->type = QueryType::RANDOM;

        return $this;
    }

    /**
     * Bind parameter.
     *
     * @param mixed $binds Binds
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function bind($binds) : Builder
    {
        if (\is_array($binds)) {
            $this->binds += $binds;
        } elseif (\is_string($binds) || $binds instanceof \Closure) {
            $this->binds[] = $binds;
        } else {
            throw new \InvalidArgumentException();
        }

        return $this;
    }

    /**
     * Creating new.
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function newQuery() : Builder
    {
        return new static($this->connection, $this->isReadOnly);
    }

    /**
     * Parsing to sql string.
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function toSql() : string
    {
        return $this->grammar->compileQuery($this);
    }

    /**
     * Set raw query.
     *
     * @param  string $raw Raw query
     *
     * @return Builder
     *
     * @throws \Exception
     *
     * @since  1.0.0
     */
    public function raw(string $raw) : Builder
    {
        if (!$this->isValidReadOnly($raw)) {
            throw new \Exception();
        }

        $this->type = QueryType::RAW;
        $this->raw  = \rtrim($raw, ';');

        return $this;
    }

    /**
     * Tests if a string contains a non read only component in case the builder is read only.
     * If the builder is not read only it will always return true
     *
     * @param  string $raw Raw query
     *
     * @return bool
     *
     * @since  1.0.0
     */
    private function isValidReadOnly($raw) : bool
    {
        if (!$this->isReadOnly) {
            return true;
        }

        $test = \strtolower($raw);

        if (\strpos($test, 'insert') !== false
            || \strpos($test, 'update') !== false
            || \strpos($test, 'drop') !== false
            || \strpos($test, 'delete') !== false
            || \strpos($test, 'create') !== false
            || \strpos($test, 'alter') !== false
        ) {
            return false;
        }

        return true;
    }

    /**
     * Make raw column selection.
     *
     * @param string|\Closure $expression Raw expression
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function selectRaw($expression) : Builder
    {
        $this->selects[null][] = $expression;

        return $this;
    }

    /**
     * Is distinct.
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function distinct() : Builder
    {
        $this->distinct = true;

        return $this;
    }

    /**
     * From.
     *
     * @param array ...$tables Tables
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function from(...$tables) : Builder
    {
        foreach ($tables as $key => $table) {
            if (\is_string($table) || $table instanceof \Closure) {
                $this->from[] = $table;
            } else {
                throw new \InvalidArgumentException();
            }
        }

        return $this;
    }

    /**
     * Make raw from.
     *
     * @param string|array|\Closure $expression Expression
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function fromRaw($expression) : Builder
    {
        $this->from[null][] = $expression;

        return $this;
    }

    /**
     * Where.
     *
     * @param Where|string|\Closure|array $columns  Columns
     * @param string|array                $operator Operator
     * @param mixed                       $values   Values
     * @param string|array                $boolean  Boolean condition
     *
     * @return Builder
     *
     * @throws \InvalidArgumentException
     *
     * @since  1.0.0
     */
    public function where($columns, $operator = null, $values = null, $boolean = 'and') : Builder
    {
        if ($operator !== null && !\is_array($operator) && !\in_array(\strtolower($operator), self::OPERATORS)) {
            throw new \InvalidArgumentException('Unknown operator.');
        }

        if (!\is_array($columns)) {
            $columns  = [$columns];
            $operator = [$operator];
            $values   = [$values];
            $boolean  = [$boolean];
        }

        $i = 0;
        foreach ($columns as $key => $column) {
            if (isset($operator[$i]) && !\in_array(\strtolower($operator[$i]), self::OPERATORS)) {
                throw new \InvalidArgumentException('Unknown operator.');
            }

            $this->wheres[self::getPublicColumnName($column)][] = [
                'column'   => $column,
                'operator' => $operator[$i],
                'value'    => $values[$i],
                'boolean'  => $boolean[$i],
            ];

            $i++;
        }

        return $this;
    }

    /**
     * Get column of where condition
     *
     * One column can have multiple where conditions.
     * TODO: maybe think about a case where there is a where condition but no column but some other identifier?
     *
     * @param mixed $column Column
     *
     * @return array|null
     *
     * @since  1.0.0
     */
    public function getWhereByColumn($column) : ?array
    {
        return $this->wheres[self::getPublicColumnName($column)] ?? null;
    }

    /**
     * Get table name of system
     *
     * @param mixed  $expression       System expression
     * @param string $systemIdentifier System identifier
     *
     * @return string|null
     *
     * @since  1.0.0
     */
    public function getTableOfSystem($expression, string $systemIdentifier) : ?string
    {
        if (($pos = \strpos($expression, $systemIdentifier . '.' . $systemIdentifier)) === false) {
            return null;
        }

        return \explode('.', $expression)[0];
    }

    /**
     * Where and sub condition.
     *
     * @param Where|string|\Closure|array $where    Where sub condition
     * @param mixed                       $operator Operator
     * @param mixed                       $values   Values
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function andWhere($where, $operator = null, $values = null) : Builder
    {
        return $this->where($where, $operator, $values, 'and');
    }

    /**
     * Where or sub condition.
     *
     * @param Where|string|\Closure|array $where    Where sub condition
     * @param mixed                       $operator Operator
     * @param mixed                       $values   Values
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function orWhere($where, $operator = null, $values = null) : Builder
    {
        return $this->where($where, $operator, $values, 'or');
    }

    /**
     * Where in.
     *
     * @param Where|string|\Closure|array $column  Column
     * @param mixed                       $values  Values
     * @param string                      $boolean Boolean condition
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function whereIn($column, $values = null, string $boolean = 'and') : Builder
    {
        $this->where($column, 'in', $values, $boolean);

        return $this;
    }

    /**
     * Where null.
     *
     * @param Where|string|\Closure|array $column  Column
     * @param string                      $boolean Boolean condition
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function whereNull($column, string $boolean = 'and') : Builder
    {
        $this->where($column, '=', null, $boolean);

        return $this;
    }

    /**
     * Where not null.
     *
     * @param Where|string|\Closure|array $column  Column
     * @param string                      $boolean Boolean condition
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function whereNotNull($column, string $boolean = 'and') : Builder
    {
        $this->where($column, '!=', null, $boolean);

        return $this;
    }

    /**
     * Group by.
     *
     * @param string|array|\Closure ...$columns Grouping result
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function groupBy(...$columns) : Builder
    {
        foreach ($columns as $key => $column) {
            if (\is_string($column) || $column instanceof \Closure) {
                $this->groups[] = $column;
            } else {
                throw new \InvalidArgumentException();
            }
        }

        return $this;
    }

    /**
     * Order by newest.
     *
     * @param string|\Closure $column Column
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function newest($column) : Builder
    {
        $this->orderBy($column, 'DESC');

        return $this;
    }

    /**
     * Order by oldest.
     *
     * @param string|\Closure $column Column
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function oldest($column) : Builder
    {
        $this->orderBy($column, 'ASC');

        return $this;
    }

    /**
     * Order by oldest.
     *
     * @param string|array|\Closure $columns Columns
     * @param string|string[]       $order   Orders
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function orderBy($columns, $order = 'DESC') : Builder
    {
        if (\is_string($columns) || $columns instanceof \Closure) {
            if (!\is_string($order)) {
                throw new \InvalidArgumentException();
            }

            if (!isset($this->orders[$order])) {
                $this->orders[$order] = [];
            }

            $this->orders[$order][] = $columns;
        } elseif (\is_array($columns)) {
            foreach ($columns as $key => $column) {
                $this->orders[\is_string($order) ? $order : $order[$key]][] = $column;
            }
        } else {
            throw new \InvalidArgumentException();
        }

        return $this;
    }

    /**
     * Offset.
     *
     * @param int $offset Offset
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function offset(int $offset) : Builder
    {
        $this->offset = $offset;

        return $this;
    }

    /**
     * Limit.
     *
     * @param int $limit Limit
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function limit(int $limit) : Builder
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Union.
     *
     * @param mixed $query Query
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function union($query) : Builder
    {
        if (!\is_array($query)) {
            $this->unions[] = $query;
        } else {
            $this->unions += $query;
        }

        return $this;
    }

    /**
     * Lock query.
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function lock()
    {
    }

    /**
     * Lock for update query.
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function lockUpdate()
    {
    }

    /**
     * Create query string.
     *
     * @return string
     *
     * @since  1.0.0
     */
    public function __toString()
    {
        return $this->grammar->compileQuery($this);
    }

    /**
     * Find query.
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function find()
    {
    }

    /**
     * Count results.
     *
     * @param string $table Table to count the result set
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function count(string $table = '*') : Builder
    {
        // todo: don't do this as string, create new object new \count(); this can get handled by the grammar parser WAY better
        return $this->select('COUNT(' . $table . ')');
    }

    /**
     * Select minimum.
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function min()
    {
    }

    /**
     * Select maximum.
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function max()
    {
    }

    /**
     * Select sum.
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function sum()
    {
    }

    /**
     * Select average.
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function avg()
    {
    }

    /**
     * Insert into columns.
     *
     * @param array ...$columns Columns
     *
     * @return Builder
     *
     * @throws \Exception
     *
     * @since  1.0.0
     */
    public function insert(...$columns) : Builder
    {
        if ($this->isReadOnly) {
            throw new \Exception();
        }

        $this->type = QueryType::INSERT;

        foreach ($columns as $key => $column) {
            $this->inserts[] = $column;
        }

        return $this;
    }

    /**
     * Table to insert into.
     *
     * @param string|\Closure $table Table
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function into($table) : Builder
    {
        $this->into = $table;

        return $this;
    }

    /**
     * Values to insert.
     *
     * @param array ...$values Values
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function values(...$values) : Builder
    {
        $this->values[] = $values;

        return $this;
    }

    /**
     * Get insert values
     *
     * @return array
     *
     * @since  1.0.0
     */
    public function getValues() : array
    {
        return $this->values;
    }

    /**
     * Values to insert.
     *
     * @param mixed  $value Values
     * @param string $type  Data type to insert
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function value($value, string $type = 'string') : Builder
    {
        \end($this->values);
        $key                  = \key($this->values);
        $this->values[$key][] = $value;
        \reset($this->values);

        return $this;
    }

    /**
     * Values to insert.
     *
     * @param array ...$sets Values
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function sets(...$sets) : Builder
    {
        $this->sets[] = $sets;

        return $this;
    }

    /**
     * Values to insert.
     *
     * @param mixed  $set  Values
     * @param string $type Data type to insert
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function set($set, string $type = 'string') : Builder
    {
        $this->sets[key($set)] = \current($set);

        return $this;
    }

    /**
     * Update columns.
     *
     * @param array ...$tables Column names to update
     *
     * @return Builder
     *
     * @throws \Exception
     *
     * @since  1.0.0
     */
    public function update(...$tables) : Builder
    {
        if ($this->isReadOnly) {
            throw new \Exception();
        }

        $this->type = QueryType::UPDATE;

        foreach ($tables as $key => $table) {
            if (\is_string($table) || $table instanceof \Closure) {
                $this->updates[] = $table;
            } else {
                throw new \InvalidArgumentException();
            }
        }

        return $this;
    }

    /**
     * Delete query
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function delete() : Builder
    {
        if ($this->isReadOnly) {
            throw new \Exception();
        }

        $this->type = QueryType::DELETE;

        return $this;
    }

    /**
     * Increment value.
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function increment()
    {
    }

    /**
     * Decrement value.
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function decrement()
    {
    }

    /**
     * Join.
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function join($table1, $table2, $column1, $opperator, $column2) : Builder
    {
        return $this;
    }

    /**
     * Join where.
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function joinWhere()
    {
    }

    /**
     * Left join.
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function leftJoin()
    {
    }

    /**
     * Left join where.
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function leftJoinWhere()
    {
    }

    /**
     * Right join.
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function rightJoin()
    {
    }

    /**
     * Right join where.
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function rightJoinWhere()
    {
    }

    /**
     * Rollback.
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function rollback()
    {
        return $this;
    }

    /**
     * On.
     *
     * @return void
     *
     * @since  1.0.0
     */
    public function on()
    {

    }

    /**
     * Merging query.
     *
     * Merging query in order to remove database query volume
     *
     * @param Builder $query Query
     *
     * @return Builder
     *
     * @since  1.0.0
     */
    public function merge(Builder $query) : Builder
    {
        return clone($this);
    }

    /**
     * Execute query.
     *
     * @return mixed
     *
     * @since  1.0.0
     */
    public function execute()
    {
        $sth = $this->connection->con->prepare($this->toSql());

        foreach ($this->binds as $key => $bind) {
            $type = self::getBindParamType($bind);

            $sth->bindParam($key, $bind, $type);
        }

        $sth->execute();

        return $sth;
    }

    /**
     * Get bind parameter type.
     *
     * @param mixed $value Value to bind
     *
     * @return mixed
     *
     * @throws \Exception
     *
     * @since  1.0.0
     */
    public static function getBindParamType($value)
    {
        if (\is_int($value)) {
            return \PDO::PARAM_INT;
        } elseif (\is_string($value) || \is_float($value)) {
            return \PDO::PARAM_STR;
        }

        throw new \Exception();
    }

    /**
     * Get column name
     *
     * @param mixed $column Column name
     *
     * @return string
     *
     * @throws \Exception
     *
     * @since  1.0.0
     */
    public static function getPublicColumnName($column) : string
    {
        if (\is_string($column)) {
            return $column;
        } elseif ($column instanceof Column) {
            return $column->getColumn();
        } elseif ($column instanceof \Closure) {
            return $column();
        } elseif ($column instanceof \Serializable) {
            return $column->serialize();
        }

        throw new \Exception();
    }
}
