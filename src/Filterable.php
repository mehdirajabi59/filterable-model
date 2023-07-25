<?php

namespace Mehdirajabi\Filterable;


use Illuminate\Database\Eloquent\Builder;
use Mehdirajabi\Filterable\Contracts\OperatorInterface;
use Mehdirajabi\Filterable\Exceptions\InvalidOperatorException;
use Mehdirajabi\Filterable\Operators\FilterBetween;
use Mehdirajabi\Filterable\Operators\FilterEqual;
use Mehdirajabi\Filterable\Operators\FilterLike;

trait Filterable
{
    protected array $columns;
    protected array $values;

    const FILTER_MODE = [
        'id'            => FilterEqual::class,
        'created_at'    => FilterBetween::class,
        'updated_at'    => FilterBetween::class
    ];

    public function scopeFilter($q, $customFilterMode = []): Builder
    {
        //filter column
        $columns  =  request('filterColumns', []);
        $values   =  request('filterValues', []);

        if (! count($columns) || ! count($values)) {
            return $q;
        }

        $this->columns  = $columns;
        $this->values   = $values;
        $this->applyFilter($q, $customFilterMode);

        return $q;
    }


    protected function applyFilter($q, $customFilterMode): void
    {
        $filterMode = array_merge($this->filterMode(), $customFilterMode);

        foreach ($this->columns as $index => $c) {

            if (! array_key_exists($index, $this->values)) continue;

            $v = $this->values[$index];

            $className = $filterMode[$c] ?? FilterLike::class;
            $class = new $className($q, $c, $v);

            if ($class instanceof OperatorInterface) {
                if ($class->isValidValue()) {
                    $class->applyFilter();
                }
            }else {
                throw new InvalidOperatorException("{$className} not implement OperatorInterface");
            }

        }
    }


    protected function filterMode(): array
    {
        if (property_exists($this, 'filterMode')) {
            return array_merge($this->filterMode, self::FILTER_MODE);
        }
        return [
            'id'            => FilterEqual::class,
            'created_at'    => FilterBetween::class,
            'updated_at'    => FilterBetween::class
        ];
    }
}
