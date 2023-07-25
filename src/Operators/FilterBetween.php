<?php

namespace Mehdirajabi\Filterable\Operators;

use Illuminate\Database\Eloquent\Builder;
use Mehdirajabi\Filterable\Contracts\OperatorInterface;
use Mehdirajabi\Filterable\Traits\RelationSplitter;

class FilterBetween implements OperatorInterface
{
    use RelationSplitter;

    private array $value = [];

    public function __construct(private readonly Builder $query, private readonly string $column, string $value)
    {
        if (preg_match('/.+\,.+/', $value)) {
            $this->value = explode(',', $value);
        }
    }

    public function isValidValue(): bool
    {
        return count($this->value) === 2;
    }
    public function applyFilter(): void
    {
        $this->isRelationColumn($this->column)
            ? $this->applyRelationWhere()
            : $this->applyWhere();
    }

    private function applyWhere(): void
    {
        $this->query->whereBetween($this->column, $this->value);
    }
    private function applyRelationWhere(): void
    {
        list($relationName, $relationColumn) = $this->splitRelationAndColumn($this->column);

        $this->query->whereHas($relationName, fn($q) => $q->whereBetween($relationColumn, $this->value));
    }


}
