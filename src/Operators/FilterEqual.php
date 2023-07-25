<?php

namespace Mehdirajabi\Filterable\Operators;

use Illuminate\Database\Eloquent\Builder;
use Mehdirajabi\Filterable\Contracts\OperatorInterface;
use Mehdirajabi\Filterable\Traits\RelationSplitter;

class FilterEqual implements OperatorInterface
{
    use RelationSplitter;
    const OPERATOR = '=';

    public function __construct(private readonly Builder $query, private readonly string $column, private string $value){}

    public function isValidValue(): bool
    {
        return ! empty($this->value);
    }
    public function applyFilter(): void
    {
        if ($this->isRelationColumn($this->column)) {
            $this->applyRelationWhere();
        }else {
            $this->applyWhere();
        }
    }

    private function applyWhere(): void
    {
        $this->query->where($this->column, static::OPERATOR, $this->value);
    }
    private function applyRelationWhere(): void
    {
        list($relationName, $relationColumn) = $this->splitRelationAndColumn($this->column);

        $this->query->whereHas($relationName, fn($q) => $q->where($relationColumn, static::OPERATOR, $this->value));
    }


}
