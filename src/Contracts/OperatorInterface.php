<?php
namespace Mehdirajabi\Filterable\Contracts;

interface OperatorInterface
{
    public function applyFilter();

    public function isValidValue();
}
