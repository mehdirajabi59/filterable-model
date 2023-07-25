<?php

namespace Mehdirajabi\Filterable\Traits;
trait RelationSplitter
{
    /**
     * @param string $r
     * @return array
     */
    public function splitRelationAndColumn(string $r): array
    {
        return explode('.', $r);
    }
    private function isRelationColumn($c) : bool
    {
        return preg_match('/^.+\..+$/', $c);
    }
}
